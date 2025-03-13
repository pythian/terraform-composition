locals {
  env    = yamldecode(file("../env.yaml"))
  inputs = yamldecode(file("./inputs.yaml"))
  az     = fileexists("../local.az.yaml") ? yamldecode(file("../local.az.yaml")) : yamldecode(file("../az.yaml"))
}

provider "azurerm" {
  subscription_id = local.env.subscription
  features {}
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

module "vnet" {
  source   = "../modules/virtual-network"
  for_each = local.inputs.virtual_networks

  address_space = lookup(each.value.address_space, local.env.environment)
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
  subnets                                 = each.value.subnets
  subnet_delegations                      = each.value.subnet_delegations
  subnet_private_service_policies_enabled = each.value.subnet_private_service_policies_enabled
  tags                                    = merge(local.inputs.tags, local.env.tags)
}

module "vnet_peerings" {
  source   = "../modules/virtual-network-peering"
  for_each = local.inputs.vnet_peerings

  name                        = each.key
  network_name                = module.vnet[each.value.network].name
  remote_network_id           = module.vnet[each.value.remote_network].id
  resource_group              = module.resource_group.name
  allow_remote_network_access = each.value.allow_remote_network_access
}

module "private_dns_zones" {
  source   = "../modules/private-dns"
  for_each = toset(local.inputs.private_dns_zones)

  name           = each.key
  resource_group = module.resource_group.name
  vnet_id        = [for network_name in lookup(local.inputs.private_dns_links, each.key, []) : module.vnet[network_name].id]
  tags           = merge(local.inputs.tags, local.env.tags)
}

output "private_dns_zone_names" {
  description = "Private dns zones created"
  value       = [for k, v in module.private_dns_zones : v.name]
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
