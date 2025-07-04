variable "acr_id" {
  description = "Azure Container Registry ID"
  type        = string

  default = ""
}

variable "app_settings" {
  description = "App settings for each web app"
  type        = map(map(string))

  default = {}
}

variable "auto_scale_profile" {
  description = "Auto scale profile for the App Service Plan"
  type = map(object({
    capacity = object({
      default = number
      minimum = number
      maximum = number
    })
    rules = list(object({
      metric_trigger = object({
        metric_name      = string
        metric_namespace = string
        time_grain       = string
        statistic        = string
        time_window      = string
        time_aggregation = string
        operator         = string
        threshold        = number
      })
      scale_action = object({
        direction = string
        type      = string
        value     = string
        cooldown  = string
      })
    }))
  }))

  default = {}
}

variable "client_certificate_mode" {
  description = "Client certificate mode for web app"
  type        = map(string)
}

variable "hostnames" {
  description = "Hostnames associated with the App Service"
  type = map(object({
    webapp_name = string
  }))

  default = {}
}

variable "ip_restriction" {
  description = "IP restriction for applications"
  type        = map(map(any))

  default = {}
}

variable "key_vault_id" {
  description = "Key Vault ID for the web app"
  type        = string
}

variable "key_vault_certificate" {
  description = "Key Vault certificate name for the web app"
  type        = map(string)

  default = {}
}

variable "location" {
  description = "Location of the App Service Plan to create"
  type        = string
}

variable "mysql_password_secret_name" {
  description = "MySQL password secret name in Key Vault"
  type        = string

  default = ""
}

variable "mysql_server_address" {
  description = "MySQL server address"
  type        = string

  default = ""
}

variable "name" {
  description = "Name of the App Service Plan to create"
  type        = string
}

variable "prefix" {
  description = "Prefix to be added to the domain"
  type        = string

  default = ""
}

variable "resource_group" {
  description = "Name of the resource group in which to create the App Service Plan"
  type        = string
}

variable "site_config" {
  description = "Site configurations for each web app"
  type        = map(map(any))

  default = {}
}

variable "sku" {
  description = "SKU name of the App Service Plan to create"
  type        = string

  default = "P0v3"
}

variable "storage_account" {
  description = "Storage account to be used for the web app"
  type = map(object({
    access_key   = optional(string, null)
    account_name = optional(string, null)
    name         = optional(string, null)
    share_name   = optional(string, null)
    type         = optional(string, null)
    mount_path   = optional(string, null)
  }))

  default = {}
}

variable "tags" {
  description = "Tags to be applied to the resources"
  type        = map(string)

  default = {}
}

variable "virtual_network_subnet_id" {
  description = "Virtual Network to attach the website into"
  type        = map(string)

  default = {}
}

variable "webapps" {
  description = "Web Applications to be deployed in this App Service Plan"
  type        = set(string)
}

locals {
  os_type    = "Linux"
  https_only = true
  key_vault_roles = [
    "Key Vault Secrets User",
    "Key Vault Certificates Officer"
  ]
}

data "azurerm_key_vault_certificate" "main" {
  for_each = { for k, v in var.hostnames : k => v }

  name         = var.key_vault_certificate[each.key]
  key_vault_id = var.key_vault_id

  depends_on = [
    azurerm_role_assignment.kv
  ]
}

data "azurerm_key_vault_secret" "main" {
  name         = var.mysql_password_secret_name
  key_vault_id = var.key_vault_id

  depends_on = [
    azurerm_role_assignment.kv
  ]
}

resource "azurerm_service_plan" "main" {
  name                = var.name
  location            = var.location
  resource_group_name = var.resource_group
  os_type             = local.os_type
  sku_name            = var.sku
  tags                = var.tags
}

resource "azurerm_user_assigned_identity" "app" {
  for_each = var.webapps

  name                = "${var.name}-${each.key}-id"
  location            = azurerm_service_plan.main.location
  resource_group_name = azurerm_service_plan.main.resource_group_name
  tags                = var.tags
}

resource "azurerm_linux_web_app" "main" {
  for_each = var.webapps

  app_settings = merge(var.app_settings[each.key], {
    MYSQL_PASSWD               = "@Microsoft.KeyVault(SecretUri=${data.azurerm_key_vault_secret.main.versionless_id})"
    MYSQL_ADDR                 = var.mysql_server_address
    CANNONICAL_URL             = "https://${format("%s.%s.com", var.prefix, each.key)}"
    CANNONICAL_URL_ALTERNATIVE = "https://${format("%s.%s.com/", var.prefix, each.key)}"
  })
  client_certificate_mode         = lookup(var.client_certificate_mode, each.key, null)
  https_only                      = local.https_only
  key_vault_reference_identity_id = azurerm_user_assigned_identity.app[each.key].id
  name                            = "${var.name}-${each.key}"
  resource_group_name             = azurerm_service_plan.main.resource_group_name
  location                        = azurerm_service_plan.main.location
  service_plan_id                 = azurerm_service_plan.main.id
  tags                            = var.tags
  virtual_network_subnet_id       = lookup(var.virtual_network_subnet_id, each.key, null)
  identity {
    type = "UserAssigned"
    identity_ids = [
      azurerm_user_assigned_identity.app[each.key].id
    ]
  }

  dynamic "site_config" {
    for_each = tomap({ "config" = lookup(var.site_config, each.key, {}) })

    content {
      always_on                                     = lookup(site_config.value, "always_on", true)
      api_definition_url                            = lookup(site_config.value, "api_definition_url", null)
      api_management_api_id                         = lookup(site_config.value, "api_management_api_id", null)
      app_command_line                              = lookup(site_config.value, "app_command_line", null)
      container_registry_use_managed_identity       = lookup(site_config.value, "container_registry_use_managed_identity", true)
      container_registry_managed_identity_client_id = azurerm_user_assigned_identity.app[each.key].client_id

      application_stack {
        docker_image_name        = lookup(site_config.value, "docker_image_name", null)
        docker_registry_url      = lookup(site_config.value, "docker_registry_url", null)
        docker_registry_username = lookup(site_config.value, "docker_registry_username", null)
        docker_registry_password = lookup(site_config.value, "docker_registry_password", null)
        dotnet_version           = lookup(site_config.value, "dotnet_version", null)
        go_version               = lookup(site_config.value, "go_version", null)
        java_version             = lookup(site_config.value, "java_version", null)
        java_server              = lookup(site_config.value, "java_server", null)
        java_server_version      = lookup(site_config.value, "java_server_version", null)
        node_version             = lookup(site_config.value, "node_version", null)
        php_version              = lookup(site_config.value, "php_version", null)
        python_version           = lookup(site_config.value, "python_version", null)
        ruby_version             = lookup(site_config.value, "ruby_version", null)
      }
      dynamic "auto_heal_setting" {
        for_each = lookup(site_config.value, "auto_heal_action_type", null) != null ? [site_config.value] : []
        content {
          action {
            action_type                    = lookup(auto_heal_setting.value, "auto_heal_action_type", null)
            minimum_process_execution_time = lookup(auto_heal_setting.value, "auto_heal_minimum_process_execution_time", null)
          }
          trigger {
            requests {
              count    = lookup(auto_heal_setting.value, "auto_heal_trigger_requests_count", null)
              interval = lookup(auto_heal_setting.value, "auto_heal_trigger_requests_interval", null)
            }
            slow_request {
              count      = lookup(auto_heal_setting.value, "auto_heal_trigger_slow_request_count", null)
              interval   = lookup(auto_heal_setting.value, "auto_heal_trigger_slow_request_interval", null)
              time_taken = lookup(auto_heal_setting.value, "auto_heal_trigger_slow_request_time_taken", null)
            }
            status_code {
              count             = lookup(auto_heal_setting.value, "auto_heal_trigger_status_code_count", null)
              interval          = lookup(auto_heal_setting.value, "auto_heal_trigger_status_code_interval", null)
              status_code_range = lookup(auto_heal_setting.value, "auto_heal_trigger_status_code_range", null)
            }
          }
        }
      }
      cors {
        allowed_origins     = lookup(site_config.value, "cors_allowed_origins", null) != null ? toset(split(",", replace(lookup(site_config.value, "cors_allowed_origins"), " ", ""))) : null
        support_credentials = lookup(site_config.value, "cors_support_credentials", false)
      }
      default_documents                 = lookup(site_config.value, "default_documents", null) != null ? tolist(split(",", replace(lookup(site_config.value, "default_documents"), " ", ""))) : null
      ftps_state                        = lookup(site_config.value, "ftps_state", "Disabled")
      health_check_path                 = lookup(site_config.value, "health_check_path", "/")
      health_check_eviction_time_in_min = lookup(site_config.value, "health_check_eviction_time_in_min", 5)
      http2_enabled                     = lookup(site_config.value, "http2_enabled", false)


      dynamic "ip_restriction" {
        for_each = lookup(var.ip_restriction, each.key, {})
        content {
          action                    = ip_restriction.value.action
          ip_address                = lookup(ip_restriction.value, "ip_address", null)
          name                      = ip_restriction.value.name
          priority                  = ip_restriction.value.priority
          service_tag               = lookup(ip_restriction.value, "service_tag", null)
          virtual_network_subnet_id = lookup(ip_restriction.value, "virtual_network_subnet_id", null)
        }
      }
      ip_restriction_default_action = lookup(site_config.value, "ip_restriction_default_action", "Allow")
      load_balancing_mode           = lookup(site_config.value, "load_balancing_mode", "LeastRequests")
      use_32_bit_worker             = lookup(site_config.value, "use_32_bit_worker", true)
      vnet_route_all_enabled        = lookup(site_config.value, "vnet_route_all_enabled", false)
      websockets_enabled            = lookup(site_config.value, "websockets_enabled", false)
      worker_count                  = lookup(site_config.value, "worker_count", 1)
    }
  }

  logs {
    detailed_error_messages = lookup(lookup(var.site_config, each.key, {}), "detailed_error_messages", false)
    failed_request_tracing  = lookup(lookup(var.site_config, each.key, {}), "failed_request_tracing", false)
    http_logs {
      file_system {
        retention_in_days = lookup(lookup(var.site_config, each.key, {}), "http_logging_retention_in_days", 7)
        retention_in_mb   = lookup(lookup(var.site_config, each.key, {}), "http_logging_retention_in_mb", 35)
      }
    }
  }

  dynamic "storage_account" {
    for_each = var.storage_account
    content {
      access_key   = storage_account.value.access_key
      account_name = storage_account.value.account_name
      name         = storage_account.value.name
      share_name   = storage_account.value.share_name
      type         = storage_account.value.type
      mount_path   = storage_account.value.mount_path
    }
  }
}

resource "azurerm_app_service_custom_hostname_binding" "main" {
  for_each = var.hostnames

  hostname = var.prefix != "" ? format("%s.%s",
    var.prefix,
    each.key,
  ) : each.key
  app_service_name    = azurerm_linux_web_app.main[each.value.webapp_name].name
  resource_group_name = azurerm_linux_web_app.main[each.value.webapp_name].resource_group_name
  thumbprint          = var.key_vault_id != "" ? azurerm_app_service_certificate.kv[each.key].thumbprint : null
  ssl_state           = var.key_vault_id != "" ? "SniEnabled" : "Disabled"
}


resource "azurerm_app_service_certificate" "kv" {
  for_each = { for k, v in var.hostnames : k => v }

  name                = each.key
  resource_group_name = var.resource_group
  location            = var.location
  key_vault_secret_id = data.azurerm_key_vault_certificate.main[each.key].versionless_id
  tags                = var.tags

  depends_on = [azurerm_role_assignment.kv]
}

resource "azurerm_role_assignment" "push_pull" {
  for_each = var.acr_id != "" ? { for k, w in azurerm_linux_web_app.main : k => w } : {}

  scope                = var.acr_id
  role_definition_name = "AcrPull"
  principal_id         = azurerm_user_assigned_identity.app[each.key].principal_id
}

resource "azurerm_role_assignment" "kv" {
  for_each = merge([
    for k, v in var.hostnames : {
      for role in local.key_vault_roles :
      "${k}-${role}" => {
        principal_id         = azurerm_user_assigned_identity.app[v.webapp_name].principal_id
        role_definition_name = role
      }
    }
  ]...)

  scope                = var.key_vault_id
  role_definition_name = each.value.role_definition_name
  principal_id         = each.value.principal_id
}



resource "azurerm_monitor_autoscale_setting" "main" {
  name                = var.name
  resource_group_name = azurerm_service_plan.main.resource_group_name
  location            = azurerm_service_plan.main.location
  target_resource_id  = azurerm_service_plan.main.id
  tags                = var.tags

  dynamic "profile" {
    for_each = var.auto_scale_profile
    content {
      name = profile.key

      capacity {
        default = profile.value.capacity.default
        minimum = profile.value.capacity.minimum
        maximum = profile.value.capacity.maximum
      }

      dynamic "rule" {
        for_each = profile.value.rules
        content {
          metric_trigger {
            operator           = rule.value.metric_trigger.operator
            metric_name        = rule.value.metric_trigger.metric_name
            metric_namespace   = rule.value.metric_trigger.metric_namespace
            metric_resource_id = azurerm_service_plan.main.id
            statistic          = rule.value.metric_trigger.statistic
            threshold          = rule.value.metric_trigger.threshold
            time_aggregation   = rule.value.metric_trigger.time_aggregation
            time_grain         = rule.value.metric_trigger.time_grain
            time_window        = rule.value.metric_trigger.time_window
          }
          scale_action {
            direction = rule.value.scale_action.direction
            type      = rule.value.scale_action.type
            value     = rule.value.scale_action.value
            cooldown  = rule.value.scale_action.cooldown
          }
        }
      }
    }
  }
}

output "id" {
  description = "App Service Plan ID"
  value       = azurerm_service_plan.main.id
}

output "location" {
  description = "App Service Plan location"
  value       = azurerm_service_plan.main.location
}

output "name" {
  description = "App Service Plan name"
  value       = azurerm_service_plan.main.name
}

output "resource_group" {
  description = "App Service Plan parent resource group"
  value       = azurerm_service_plan.main.resource_group_name
}

output "webapp_hostnames" {
  description = "Web Applications deployed fqdns"
  value = {
    for k, w in azurerm_linux_web_app.main : k => concat(
      [w.default_hostname],
      [for binding in azurerm_app_service_custom_hostname_binding.main : binding.hostname if binding.app_service_name == w.name]
    )
  }
}

output "webapp_ids" {
  description = "Web Applications deployed ids"
  value       = [for w in azurerm_linux_web_app.main : w.id]
}

output "webapp_ids_map" {
  description = "Web Applications deployed ids in map format"
  value       = { for k, w in azurerm_linux_web_app.main : k => w.id }
}

output "webapp_names" {
  description = "Web Applications deployed names"
  value       = [for w in azurerm_linux_web_app.main : w.name]
}
