locals {
  az           = fileexists("../local.az.yaml") ? yamldecode(file("../local.az.yaml")) : yamldecode(file("../az.yaml"))
  env          = yamldecode(file("../env.yaml"))
  hostnames    = flatten([for k, v in local.inputs.application_gateway_backend_settings : module.app_service.webapp_hostnames[k]])
  inputs       = yamldecode(file("./inputs.yaml"))
  networks     = yamldecode((file("../networks.yaml")))
  storage_type = "AzureFiles"
}

provider "azurerm" {
  subscription_id = local.env.subscription
  features {}
}

data "terraform_remote_state" "shared" {
  backend = "azurerm"

  config = {
    container_name       = "tfstate"
    key                  = "build.terraform.tfstate"
    resource_group_name  = "cnx-build-cus-tfstate"
    subscription_id      = "9712bfef-07af-4a61-804e-b2fa08462f70"
    storage_account_name = "cnxbuildcustfstate"
  }
}


module "application_gateway" {
  source = "../modules/app-gateway"

  autoscale_configuration = local.inputs.application_gateway_autoscale_configuration
  backend_settings = { for k, v in local.inputs.application_gateway_backend_settings : k => {
    fqdns        = module.app_service.webapp_hostnames[k]
    http_setting = lookup(local.inputs.application_gateway_backend_settings[k], "http_setting")
    }
  }
  frontend_settings = {
    for k, v in local.inputs.application_gateway_frontend_settings :
    k => merge(v, {
      public_ip_id = module.public_ip.id
      hostnames    = local.hostnames
      },
    )
  }
  key_vault_id = module.key_vault.id
  location     = local.env.location
  name = coalesce(local.inputs.application_gateway_name_override,
    format("%s-%s-%s-%s",
      local.az.prefix,
      local.env.environment,
      local.env.location_short,
      local.inputs.application_gateway_name,
    )
  )
  resource_group    = module.resource_group.name
  routing_rules     = local.inputs.application_gateway_routing_rules
  ssl_configuration = local.inputs.application_gateway_ssl_configuration
  subnet_id         = module.vnet[local.inputs.application_gateway_virtual_network].subnet_ids[local.inputs.application_gateway_subnet]
  tags              = merge(local.inputs.tags, local.env.tags)
}

module "app_service" {
  source = "../modules/app-service"

  auto_scale_profile      = local.inputs.app_service_auto_scale_profile
  acr_id                  = data.terraform_remote_state.shared.outputs.container_registry_id
  app_settings            = local.inputs.app_service_app_settings
  client_certificate_mode = local.inputs.app_service_client_certificate_mode
  hostnames               = local.inputs.app_service_hostnames
  key_vault_id            = module.key_vault.id
  key_vault_certificate   = local.inputs.app_service_key_vault_certificates
  ip_restriction = local.inputs.app_service_ip_restriction != {} ? {
    for app, rules in local.inputs.app_service_ip_restriction : app => {
      for name, rule in rules : name => merge(rule, {
        ip_address = "${module.public_ip.ip_address}/32"
      })
    }
  } : {}
  location                   = local.env.location
  mysql_server_address       = "${module.mysql.name}.mysql.database.azure.com"
  mysql_password_secret_name = local.inputs.app_service_mysql_password_secret_name
  name = coalesce(local.inputs.app_service_name_override,
    format("%s-%s-%s-%s",
      local.az.prefix,
      local.env.environment,
      local.env.location_short,
      local.inputs.app_service_name,
    )
  )
  prefix         = local.env.environment != "prod" ? local.env.environment : "prd" # "www" #temporary url prefix
  resource_group = module.resource_group.name
  site_config    = local.inputs.app_service_site_config
  sku            = local.inputs.app_service_sku
  storage_account = { for k, v in local.inputs.app_service_storage_mount_path : k => {
    access_key   = module.storage_account.access_key
    account_name = module.storage_account.name
    name         = module.storage_account.share_names[k]
    share_name   = module.storage_account.share_names[k]
    type         = local.storage_type
    mount_path   = v
    }
  }
  tags                      = merge(local.inputs.tags, local.env.tags)
  virtual_network_subnet_id = { for k, v in local.inputs.app_service_virtual_network_subnet : k => module.vnet[v.virtual_network].subnet_ids[v.subnet] }
  webapps                   = local.inputs.app_service_webapps
}

module "key_vault" {
  source = "../modules/key-vault"

  key_vault_roles = local.inputs.key_vault_roles
  location        = local.env.location
  name = coalesce(local.inputs.key_vault_name_override,
    format("%s-%s-%s-%s",
      local.az.prefix,
      local.env.environment,
      local.env.location_short,
      local.inputs.key_vault_name,
    )
  )
  public_network_access_enabled = local.inputs.key_vault_public_network_access_enabled
  purge_protection              = local.inputs.key_vault_purge_protection
  resource_group                = module.key_vault_resource_group.name
  retention_days                = local.inputs.key_vault_retention_days
  secret_keys                   = local.inputs.key_vault_secret_keys
  secrets                       = local.inputs.key_vault_secrets
  sku                           = local.inputs.key_vault_sku
  tags                          = merge(local.inputs.tags, local.env.tags)
  use_precreated_secrets        = local.inputs.key_vault_use_precreated_secrets
}

module "key_vault_resource_group" {
  source = "../modules/resource-group"

  location = local.env.location
  name = coalesce(local.inputs.key_vault_resource_group_name_override,
    format("%s-%s-%s-%s",
      local.az.prefix,
      local.env.environment,
      local.env.location_short,
      local.inputs.key_vault_resource_group_name,
    )
  )
  tags = merge(local.inputs.tags, local.env.tags)
}

module "mysql" {
  source = "../modules/mysql-flexible"

  administrator_login   = local.inputs.mysql_administrator_login
  backup_retention_days = local.inputs.mysql_backup_retention_days
  databases             = local.inputs.mysql_databases
  key_vault_id          = module.key_vault.id
  location              = local.env.location
  name = coalesce(local.inputs.mysql_name_override,
    format("%s-%s-%s-%s",
      local.az.prefix,
      local.env.environment,
      local.env.location_short,
      local.inputs.mysql_name,
    )
  )
  resource_group       = module.resource_group.name
  server_configuration = local.inputs.mysql_server_configuration
  sku                  = local.inputs.mysql_sku
  tags                 = merge(local.inputs.tags, local.env.tags)
  zone                 = local.inputs.mysql_zone
}

module "private_dns_zones" {
  source   = "../modules/private-dns"
  for_each = toset(local.inputs.private_dns_zones)

  name           = each.key
  resource_group = module.resource_group.name
  vnet_id        = { for network_name in lookup(local.inputs.private_dns_links, each.key, []) : network_name => module.vnet[network_name].id }
  tags           = merge(local.inputs.tags, local.env.tags)
}

### Private Endpoints
module "app_service_private-endpoints" {
  source   = "../modules/private-endpoint"
  for_each = local.inputs.app_service_private_endpoints

  location = local.env.location
  name = coalesce(each.value.name_override,
    format("%s-%s-%s-%s",
      local.az.prefix,
      local.env.environment,
      local.env.location_short,
      each.value.name,
    )
  )
  private_dns_zone_id = [module.private_dns_zones[each.value.private_dns_zone].id]
  private_service_connection = merge(each.value.private_service_connection, {
    private_connection_resource_id = module.app_service.webapp_ids_map[each.value.name]
  })
  resource_group = module.resource_group.name
  subnet_id      = module.vnet[local.inputs.private_endpoints_virtual_network].subnet_ids[local.inputs.private_endpoints_subnet]
  tags           = merge(local.inputs.tags, local.env.tags)
}

module "key_vault_private-endpoints" {
  source   = "../modules/private-endpoint"
  for_each = local.inputs.key_vault_private_endpoints

  location = local.env.location
  name = coalesce(each.value.name_override,
    format("%s-%s-%s-%s",
      local.az.prefix,
      local.env.environment,
      local.env.location_short,
      each.value.name,
    )
  )
  private_dns_zone_id = [module.private_dns_zones[each.value.private_dns_zone].id]
  private_service_connection = merge(each.value.private_service_connection, {
    private_connection_resource_id = module.key_vault.id
  })
  resource_group = module.resource_group.name
  subnet_id      = module.vnet[local.inputs.private_endpoints_virtual_network].subnet_ids[local.inputs.private_endpoints_subnet]
  tags           = merge(local.inputs.tags, local.env.tags)
}

module "mysql_private-endpoints" {
  source   = "../modules/private-endpoint"
  for_each = local.inputs.mysql_private_endpoints

  location = local.env.location
  name = coalesce(each.value.name_override,
    format("%s-%s-%s-%s",
      local.az.prefix,
      local.env.environment,
      local.env.location_short,
      each.value.name,
    )
  )
  private_dns_zone_id = [module.private_dns_zones[each.value.private_dns_zone].id]
  private_service_connection = merge(each.value.private_service_connection, {
    private_connection_resource_id = module.mysql.id
  })
  resource_group = module.resource_group.name
  subnet_id      = module.vnet[local.inputs.private_endpoints_virtual_network].subnet_ids[local.inputs.private_endpoints_subnet]
  tags           = merge(local.inputs.tags, local.env.tags)
}

module "storage_account_private-endpoints" {
  source   = "../modules/private-endpoint"
  for_each = local.inputs.storage_account_private_endpoints

  location = local.env.location
  name = coalesce(each.value.name_override,
    format("%s-%s-%s-%s",
      local.az.prefix,
      local.env.environment,
      local.env.location_short,
      each.value.name,
    )
  )
  private_dns_zone_id = [module.private_dns_zones[each.value.private_dns_zone].id]
  private_service_connection = merge(each.value.private_service_connection, {
    private_connection_resource_id = module.storage_account.id
  })
  resource_group = module.resource_group.name
  subnet_id      = module.vnet[local.inputs.private_endpoints_virtual_network].subnet_ids[local.inputs.private_endpoints_subnet]
  tags           = merge(local.inputs.tags, local.env.tags)
}

###

module "public_ip" {
  source = "../modules/public-ip"

  location = local.env.location
  name = coalesce(local.inputs.public_ip_name_override,
    format("%s-%s-%s-%s",
      local.az.prefix,
      local.env.environment,
      local.env.location_short,
      local.inputs.public_ip_name
    )
  )
  resource_group = module.resource_group.name
  tags           = merge(local.inputs.tags, local.env.tags)
}

module "resource_group" {
  source = "../modules/resource-group"

  location = local.env.location
  name = coalesce(local.inputs.resource_group_name_override,
    format("%s-%s-%s-%s",
      local.az.prefix,
      local.env.environment,
      local.env.location_short,
      local.inputs.resource_group_name,
    )
  )
  tags = merge(local.inputs.tags, local.env.tags)
}

module "storage_account" {
  source = "../modules/storage-account"

  kind     = local.inputs.storage_account_kind
  location = local.env.location
  name = coalesce(local.inputs.resource_group_name_override,
    format("%s%s%s%s",
      local.az.prefix,
      local.env.environment,
      local.env.location_short,
      local.inputs.storage_account_name,
    )
  )
  network_rules = {
    for k, v in local.inputs.storage_account_virtual_networks_allowed : k => {
      bypass         = local.inputs.storage_account_bypass
      default_action = local.inputs.storage_account_default_action
      ip_rules       = local.inputs.storage_account_ip_rules
    }
  }
  public_network_access_enabled = local.inputs.storage_account_public_network_access_enabled
  resource_group                = module.resource_group.name
  shares                        = local.inputs.storage_account_shares
  tags                          = merge(local.inputs.tags, local.env.tags)
  tier                          = local.inputs.storage_account_tier
}

module "vnet" {
  source   = "../modules/virtual-network"
  for_each = local.inputs.virtual_networks

  address_space = lookup(lookup(local.networks, local.env.environment), each.key).address_space
  location      = local.env.location
  name = coalesce(each.value.name_override,
    format("%s-%s-%s-%s",
      local.az.prefix,
      local.env.environment,
      local.env.location_short,
      each.value.name,
    )
  )
  resource_group                          = module.resource_group.name
  subnets                                 = lookup(lookup(local.networks, local.env.environment), each.key).subnets
  subnet_delegations                      = each.value.subnet_delegations
  subnet_services                         = each.value.subnet_services
  subnet_private_service_policies_enabled = each.value.subnet_private_service_policies_enabled
  tags                                    = merge(local.inputs.tags, local.env.tags)
}

module "vnet_peerings" {
  source   = "../modules/virtual-network-peering"
  for_each = local.inputs.vnet_peerings

  allow_remote_network_access = each.value.allow_remote_network_access
  name                        = each.key
  network_name                = module.vnet[each.value.network].name
  remote_network_id           = module.vnet[each.value.remote_network].id
  resource_group              = module.resource_group.name
}

# output "application_gateway_id" {
#   description = "Application gateway id"
#   value       = module.application_gateway.id
# }

# output "application_gateway_name" {
#   description = "Application gateway name"
#   value       = module.application_gateway.name
# }

output "app_service_plan_id" {
  description = "App service plan id"
  value       = module.app_service.id
}

output "app_service_plan_name" {
  description = "App service plan name"
  value       = module.app_service.name
}

output "app_service_webapp_ids" {
  description = "App service plan deployed web applications ids"
  value       = module.app_service.webapp_ids
}

output "app_service_webapp_names" {
  description = "App service plan deployed web applications names"
  value       = module.app_service.webapp_names
}

output "key_vault_resource_group_id" {
  description = "Key Vault resource group ID"
  value       = module.key_vault_resource_group.id
}

output "key_vault_resource_group_name" {
  description = "Key Vault resource group name"
  value       = module.key_vault_resource_group.name
}

output "key_vault_id" {
  description = "Key Vault ID"
  value       = module.key_vault.id
}

output "key_vault_name" {
  description = "Key Vault name"
  value       = module.key_vault.name
}

output "mysql_id" {
  description = "MySQL Flexible Server ID"
  value       = module.mysql.id
}

output "mysql_name" {
  description = "MySQL Flexible Server name"
  value       = module.mysql.name
}

output "private_dns_zone_names" {
  description = "Private dns zones created"
  value       = [for k, v in module.private_dns_zones : v.name]
}

output "public_ip_id" {
  description = "Public IP ID"
  value       = module.public_ip.id
}

output "resource_group_id" {
  description = "Resource group ID"
  value       = module.resource_group.id
}

output "resource_group_location" {
  description = "Resource group location"
  value       = module.resource_group.location
}

output "resource_group_name" {
  description = "Resource group name"
  value       = module.resource_group.name
}

output "subnets" {
  description = "List of subnets created"
  value       = { for k, v in module.vnet : v.name => v.subnet_ids }
}

output "storage_account_name" {
  description = "Storage account name"
  value       = module.storage_account.name
}

output "virtual_networks_ids" {
  description = "List of virtual networks IDs"
  value       = [for k, v in module.vnet : v.id]
}

output "virtual_networks_names" {
  description = "List of virtual networks Names"
  value       = [for k, v in module.vnet : v.name]
}

output "virtual_network_peerings_ids" {
  description = "List of Virtual Network peerings ids"
  value       = [for k, v in module.vnet_peerings : v.id]
}

output "virtual_network_peerings_names" {
  description = "List of Virtual Network peerings names"
  value       = [for k, v in module.vnet_peerings : v.name]
}
