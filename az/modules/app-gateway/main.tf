variable "autoscale_configuration" {
  description = "Autoscale settings for the application gateway"
  type = object({
    min_capacity = number
    max_capacity = number
  })
}

variable "backend_settings" {
  description = "Backend address pools to create"
  type = map(object({
    fqdns = list(string)
    http_setting = optional(object({
      affinity_cookie_name                = optional(string, "ApplicationGatewayAffinity")
      cookie_based_affinity               = optional(string, "Disabled")
      host_name                           = optional(string, null)
      path                                = optional(string, "/")
      port                                = optional(number, 443)
      protocol                            = optional(string, "Https")
      pick_host_name_from_backend_address = optional(bool, true)
      request_timeout                     = optional(number, 20)
    }), {})
  }))
}

variable "enable_http2" {
  description = "Enables HTTP2 in the application gateway"
  type        = bool

  default = true
}

variable "frontend_settings" {
  description = "Frontend address configurations"
  type = map(object({
    hostnames    = list(string)
    port         = string
    protocol     = string
    public_ip_id = string
    require_sni  = optional(bool, false)
  }))
}

variable "key_vault_id" {
  description = "Key Vault ID for the web app"
  type        = string

  default = ""
}

variable "location" {
  description = "The location of the application gateway to create"
  type        = string
}

variable "name" {
  description = "Name of the application gateway to create"
  type        = string
}

variable "probes" {
  description = "Probes to be added to the application gateway"
  type = map(object({
    host                                = optional(string, null)
    interval                            = optional(number, 30)
    path                                = optional(string, "/")
    pick_host_name_from_backend_address = optional(bool, true)
    port                                = optional(number, null)
    protocol                            = optional(string, "Https")
    timeout                             = optional(number, 30)
    unhealthy_threshold                 = optional(number, 3)
  }))

  default = {}
}

variable "resource_group" {
  description = "Name of the resource group in which to deploy the application gateway"
  type        = string
}

variable "routing_rules" {
  description = "Defines the routing rules to be added between backend and frontend configurations"
  type = map(object({
    backend_address_pool  = string
    backend_http_settings = string
    http_listener         = string
    priority              = optional(number, null)
    rule_type             = optional(string, "Basic")
  }))
}

variable "sku" {
  description = "Sku for the application gateway"
  type        = string

  default = "Standard_v2"
}

variable "ssl_configuration" {
  description = "ssl configuration for the application gateway"
  type = object({
    ssl_certificate_name = string
    ssl_certificate_path = optional(string, null)
    ssl_secret_name      = optional(string, null)
  })
}

variable "subnet_id" {
  description = "ID of the subnet to attach the application gateway"
  type        = string
}

variable "tags" {
  description = "Tags to associate with the application gateway"
  type        = map(string)

  default = {}
}

variable "zones" {
  description = "Zones where the application gateway is deployed"
  type        = list(string)

  default = ["1", "2", "3"]
}



locals {
  backend_address_pool_name         = "${var.name}-beap"
  capacity                          = 0
  fips_enabled                      = false
  force_firewall_policy_association = false
  frontend_port_name                = "${var.name}-feport"
  frontend_ip_configuration_name    = "${var.name}-feip"
  gateway_ip_configuration          = "appGatewayIpConfig"
  http_setting_name                 = "${var.name}-be-htst"
  identity_type                     = "UserAssigned"
  listener_name                     = "${var.name}-httplstn"
  request_routing_rule_name         = "${var.name}-rqrt"
  redirect_configuration_name       = "${var.name}-rdrcfg"
  rewrite_rule_sets = {
    "default" = {
      name = "set-x-forwarded-proto"
      rewrite_rules = [
        {
          name          = "set-x-forwarded-proto-rule"
          rule_sequence = 100
          request_header_configurations = [
            {
              header_name  = "X-Forwarded-Proto"
              header_value = "https"
            }
          ]
          response_header_configurations = []
        }
      ]
    }
  }
}

data "azurerm_key_vault_certificate" "main" {

  name         = var.ssl_configuration.ssl_secret_name
  key_vault_id = var.key_vault_id

  depends_on = [
    azurerm_role_assignment.kv
  ]
}

resource "azurerm_application_gateway" "main" {
  enable_http2                      = var.enable_http2
  fips_enabled                      = local.fips_enabled
  force_firewall_policy_association = local.force_firewall_policy_association
  location                          = var.location
  identity {
    type         = local.identity_type
    identity_ids = [azurerm_user_assigned_identity.main.id]
  }
  name                = var.name
  resource_group_name = var.resource_group
  tags                = var.tags
  zones               = var.zones

  autoscale_configuration {
    min_capacity = var.autoscale_configuration.min_capacity
    max_capacity = var.autoscale_configuration.max_capacity
  }

  dynamic "backend_address_pool" {
    for_each = var.backend_settings

    content {
      name  = format("%s-%s", local.backend_address_pool_name, backend_address_pool.key)
      fqdns = backend_address_pool.value.fqdns
    }
  }

  dynamic "rewrite_rule_set" {
    for_each = local.rewrite_rule_sets

    content {
      name = rewrite_rule_set.value.name

      dynamic "rewrite_rule" {
        for_each = rewrite_rule_set.value.rewrite_rules

        content {
          name          = rewrite_rule.value.name
          rule_sequence = rewrite_rule.value.rule_sequence

          dynamic "request_header_configuration" {
            for_each = rewrite_rule.value.request_header_configurations

            content {
              header_name  = request_header_configuration.value.header_name
              header_value = request_header_configuration.value.header_value
            }
          }

          dynamic "response_header_configuration" {
            for_each = rewrite_rule.value.response_header_configurations

            content {
              header_name  = response_header_configuration.value.header_name
              header_value = response_header_configuration.value.header_value
            }
          }
        }
      }
    }
  }

  dynamic "backend_http_settings" {
    for_each = var.backend_settings

    content {
      affinity_cookie_name                = backend_http_settings.value.http_setting.affinity_cookie_name
      cookie_based_affinity               = backend_http_settings.value.http_setting.cookie_based_affinity
      name                                = format("%s-%s", local.http_setting_name, backend_http_settings.key)
      path                                = backend_http_settings.value.http_setting.path
      port                                = backend_http_settings.value.http_setting.port
      protocol                            = backend_http_settings.value.http_setting.protocol
      pick_host_name_from_backend_address = backend_http_settings.value.http_setting.pick_host_name_from_backend_address
      request_timeout                     = backend_http_settings.value.http_setting.request_timeout
      probe_name                          = var.probes != {} ? backend_http_settings.key : null
    }
  }

  dynamic "frontend_ip_configuration" {
    for_each = var.frontend_settings

    content {
      name                 = format("%s-%s", local.frontend_ip_configuration_name, frontend_ip_configuration.key)
      public_ip_address_id = frontend_ip_configuration.value.public_ip_id
    }
  }

  dynamic "frontend_port" {
    for_each = var.frontend_settings

    content {
      name = format("%s-%s", local.frontend_port_name, frontend_port.key)
      port = frontend_port.value.port
    }
  }

  gateway_ip_configuration {
    name      = local.gateway_ip_configuration
    subnet_id = var.subnet_id
  }

  dynamic "http_listener" {
    for_each = var.frontend_settings

    content {
      name                           = format("%s-%s", local.listener_name, http_listener.key)
      frontend_ip_configuration_name = format("%s-%s", local.frontend_ip_configuration_name, http_listener.key)
      frontend_port_name             = format("%s-%s", local.frontend_port_name, http_listener.key)
      host_names                     = http_listener.value.hostnames
      protocol                       = http_listener.value.protocol
      require_sni                    = http_listener.value.require_sni
      ssl_certificate_name           = var.ssl_configuration.ssl_certificate_name
    }
  }

  dynamic "probe" {
    for_each = var.probes

    content {
      name                                      = probe.key
      host                                      = probe.value.host
      interval                                  = probe.value.interval
      path                                      = probe.value.path
      pick_host_name_from_backend_http_settings = probe.value.pick_host_name_from_backend_address
      port                                      = probe.value.port
      protocol                                  = probe.value.protocol
      timeout                                   = probe.value.timeout
      unhealthy_threshold                       = probe.value.unhealthy_threshold

      match {
        status_code = ["200-399"]
      }
    }

  }

  dynamic "request_routing_rule" {
    for_each = var.routing_rules

    content {
      backend_address_pool_name  = format("%s-%s", local.backend_address_pool_name, request_routing_rule.value.backend_address_pool)
      backend_http_settings_name = format("%s-%s", local.http_setting_name, request_routing_rule.value.backend_http_settings)
      http_listener_name         = format("%s-%s", local.listener_name, request_routing_rule.value.http_listener)
      name                       = format("%s-%s", local.request_routing_rule_name, request_routing_rule.key)
      priority                   = request_routing_rule.value.priority
      rule_type                  = request_routing_rule.value.rule_type
    }
  }

  sku {
    capacity = local.capacity
    name     = var.sku
    tier     = var.sku
  }

  ssl_certificate {
    name                = var.ssl_configuration.ssl_certificate_name
    data                = var.ssl_configuration.ssl_certificate_path != null ? filebase64(var.ssl_configuration.ssl_certificate_path) : null
    key_vault_secret_id = var.ssl_configuration.ssl_secret_name != null ? data.azurerm_key_vault_certificate.main.versionless_secret_id : null
  }
}

resource "azurerm_user_assigned_identity" "main" {
  name                = var.name
  location            = var.location
  resource_group_name = var.resource_group
  tags                = var.tags
}

resource "azurerm_role_assignment" "kv" {
  scope                = var.key_vault_id
  role_definition_name = "Key Vault Certificate User"
  principal_id         = azurerm_user_assigned_identity.main.principal_id
}


output "id" {
  description = "Application Gateway ID"
  value       = azurerm_application_gateway.main.id
}

output "location" {
  description = "Application Gateway location"
  value       = azurerm_application_gateway.main.location
}

output "name" {
  description = "Application Gateway name"
  value       = azurerm_application_gateway.main.name
}

output "resource_group" {
  description = "Application Gateway parent resource group"
  value       = azurerm_application_gateway.main.resource_group_name
}
