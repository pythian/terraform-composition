variable "container_registry_id" {
  description = "id of the container registry to use for container app images"
  type        = string
}

variable "container" {
  description = "container definitions to use for the container app"
  type = object({
    args    = optional(list(string), null)
    command = optional(list(string), null)
    cpu     = optional(number, null)
    env = optional(list(object({
      name        = string
      secret_name = optional(string, null)
      value       = optional(string, null)
    })), [])
    image = string
    liveness_probe = optional(object({
      failure_threshold = optional(number, null)
      http_get = optional(object({
        path      = optional(string, null)
        port      = number
        transport = optional(string, "HTTP")
      }), null)
      initial_delay_seconds = optional(number, null)
      interval_seconds      = optional(number, null)
      timeout_seconds       = optional(number, null)
    }), null)
    memory = optional(string, null)
    name   = string
    readiness_probe = optional(object({
      failure_threshold = optional(number, null)
      http_get = optional(object({
        path      = optional(string, null)
        port      = number
        transport = optional(string, "HTTP")
      }), null)
      interval_seconds  = optional(number, null)
      success_threshold = optional(number, null)
      timeout_seconds   = optional(number, null)
    }), null)
    secrets = optional(list(object({
      name                = string
      key_vault_secret_id = optional(string, null)
      identity            = optional(string, null)
      value               = optional(string, null)
    })), [])
    startup_probe = optional(object({
      failure_threshold = optional(number, null)
      http_get = optional(object({
        path      = optional(string, null)
        port      = number
        transport = optional(string, "HTTP")
      }), null)
      interval_seconds  = optional(number, null)
      success_threshold = optional(number, null)
      timeout_seconds   = optional(number, null)
    }), null)
  })
}

variable "custom_domain" {
  description = "Custom domain to use for the container app"
  type        = string

  default = ""
}

variable "identity_type" {
  description = "Managed identity type to be used by the container app"
  type        = string

  default = "UserAssigned"
}

variable "ingress" {
  description = "values to use for the ingress"
  type = object({
    allow_insecure_connections = optional(bool, false)
    custom_domain = optional(map(object({
      certificate_binding_type = optional(string, "Disabled")
      certificate_id           = string
      name                     = string
    })), {})
    client_certificate_mode = optional(string, "ignore")
    external_enabled        = optional(bool, false)
    fqdn                    = optional(string)
    target_port             = optional(number)
    transport               = optional(string, "auto")
  })

  default = {}
}

variable "key_vault_id" {
  description = "Id of the key vault to use for the container app"
  type        = string

  default = ""
}

variable "key_vault_certificates" {
  description = "Name of the key vault secret to use for the container app"
  type        = map(string)
  default     = {}
}

variable "location" {
  description = "Location of the container app to create"
  type        = string
}

variable "log_analytics_workspace_id" {
  description = "Id of the log analytics workspace to use for container app logs"
  type        = string

  default = ""
}

variable "name" {
  description = "Name of the container app to create"
  type        = string
}

variable "private_network_subnet_id" {
  description = "Id of the private subnet to use for the container app VNET Integration"
  type        = string

  default = ""
}

variable "replicas" {
  description = "Number of replicas to create for the container app"
  type = object({
    max = optional(number, null)
    min = optional(number, null)
  })

  default = {}
}

variable "resource_group" {
  description = "Name of the resource group in which to create the container app"
  type        = string
}

variable "storage" {
  description = "Storage account to use for the container app environment"
  type = map(object({
    access_key  = string
    access_mode = optional(string, "ReadWrite")
    mount_path  = optional(string, null)
    name        = string
    share_name  = string
  }))

  default = {}
}

variable "tags" {
  description = "Tags to apply to the container app"
  type        = map(string)

  default = {}
}

variable "workload_profile" {
  description = "Workload profile to use for the container app"
  type        = map(string)

  default = {}

}

locals {
  container_registry_domain = "azurecr.io"
  latest_revision           = true
  revision_mode             = "Single"
  traffic_weight_percentage = 100
  mount_options             = "dir_mode=0777,file_mode=0777,uid=33,gid=33,mfsymlinks,nobrl,cache=none"
}

data "azurerm_container_app_environment_certificate" "cert" {
  for_each = var.key_vault_certificates

  name                         = each.value
  container_app_environment_id = azurerm_container_app_environment.env.id
}


resource "azurerm_user_assigned_identity" "main" {
  name                = format("%s-identity", var.name)
  location            = var.location
  resource_group_name = var.resource_group
  tags                = var.tags
}

resource "azurerm_container_app_environment" "env" {
  location                       = var.location
  log_analytics_workspace_id     = var.log_analytics_workspace_id == "" ? null : var.log_analytics_workspace_id
  internal_load_balancer_enabled = var.private_network_subnet_id == "" ? false : true
  infrastructure_subnet_id       = var.private_network_subnet_id == "" ? null : var.private_network_subnet_id
  name                           = var.name
  resource_group_name            = var.resource_group
  tags                           = var.tags
  dynamic "workload_profile" {
    for_each = var.workload_profile == {} ? [] : [for v in [var.workload_profile] : v]
    content {
      name                  = try(workload_profile.value.name)
      workload_profile_type = try(workload_profile.value.workload_profile_type)
      maximum_count         = try(workload_profile.value.maximum_count)
      minimum_count         = try(workload_profile.value.minimum_count)
    }
  }

  lifecycle {
    ignore_changes = [
      infrastructure_resource_group_name,
    ]
  }
}

resource "azurerm_container_app" "main" {
  container_app_environment_id = azurerm_container_app_environment.env.id
  name                         = var.name
  resource_group_name          = var.resource_group
  revision_mode                = local.revision_mode
  tags                         = var.tags

  identity {
    type         = var.identity_type
    identity_ids = [azurerm_user_assigned_identity.main.id]
  }

  dynamic "ingress" {
    for_each = [for v in [var.ingress] : v]
    content {
      allow_insecure_connections = try(ingress.value.allow_insecure_connections)
      client_certificate_mode    = try(ingress.value.client_certificate_mode)
      external_enabled           = try(ingress.value.external_enabled)
      fqdn                       = try(ingress.value.fqdn)
      target_port                = try(ingress.value.target_port)
      transport                  = try(ingress.value.transport)
      traffic_weight {
        percentage      = local.traffic_weight_percentage
        latest_revision = local.latest_revision
      }

      dynamic "custom_domain" {
        for_each = ingress.value.custom_domain == null ? [] : [for v in ingress.value.custom_domain : v]
        content {
          certificate_binding_type = try(custom_domain.value.certificate_binding_type)
          certificate_id           = try(custom_domain.value.certificate_id)
          name                     = try(custom_domain.value.name)
        }
      }
    }
  }

  registry {
    server   = format("%s.%s", basename(var.container_registry_id), local.container_registry_domain)
    identity = azurerm_user_assigned_identity.main.id
  }

  dynamic "secret" {
    for_each = var.container.secrets
    content {
      name                = secret.value.name
      value               = try(secret.value.value)
      key_vault_secret_id = try(secret.value.key_vault_secret_id)
      identity            = try(secret.value.identity)
    }
  }

  template {
    max_replicas = try(var.replicas.max)
    min_replicas = try(var.replicas.min)
    dynamic "container" {
      for_each = [for v in [var.container] : v]
      content {
        args    = try(container.value.args)
        command = try(container.value.command)
        cpu     = try(container.value.cpu)
        image   = format("%s.%s/%s", basename(var.container_registry_id), local.container_registry_domain, container.value.image)
        memory  = try(container.value.memory)
        name    = try(container.value.name)

        dynamic "env" {
          for_each = container.value.env
          content {
            name        = env.value.name
            secret_name = env.value.secret_name
            value       = env.value.value
          }
        }

        dynamic "liveness_probe" {
          for_each = container.value.liveness_probe == null ? [] : [for v in [container.value.liveness_probe] : v]
          content {
            failure_count_threshold = try(liveness_probe.value.failure_threshold)
            initial_delay           = try(liveness_probe.value.initial_delay_seconds)
            path                    = try(liveness_probe.value.http_get.path)
            interval_seconds        = try(liveness_probe.value.interval_seconds)
            port                    = liveness_probe.value.http_get.port
            transport               = liveness_probe.value.http_get.transport
            timeout                 = try(liveness_probe.value.timeout_seconds)
          }
        }

        dynamic "readiness_probe" {
          for_each = container.value.readiness_probe == null ? [] : [for v in [container.value.readiness_probe] : v]
          content {
            failure_count_threshold = try(readiness_probe.value.failure_threshold)
            path                    = try(readiness_probe.value.http_get.path)
            interval_seconds        = try(readiness_probe.value.interval_seconds)
            port                    = readiness_probe.value.http_get.port
            transport               = readiness_probe.value.http_get.transport
            timeout                 = try(readiness_probe.value.timeout_seconds)
          }
        }

        dynamic "startup_probe" {
          for_each = container.value.startup_probe == null ? [] : [for v in [container.value.startup_probe] : v]
          content {
            failure_count_threshold = try(startup_probe.value.failure_threshold)
            path                    = try(startup_probe.value.http_get.path)
            interval_seconds        = try(startup_probe.value.interval_seconds)
            port                    = startup_probe.value.http_get.port
            transport               = startup_probe.value.http_get.transport
            timeout                 = try(startup_probe.value.timeout_seconds)
          }
        }

        dynamic "volume_mounts" {
          for_each = var.storage
          content {
            name = volume_mounts.key
            path = try(volume_mounts.value.mount_path)
          }
        }
      }
    }

    dynamic "volume" {
      for_each = var.storage
      content {
        name          = volume.key
        storage_name  = "${azurerm_container_app_environment.env.name}-${volume.key}"
        storage_type  = "AzureFile"
        mount_options = local.mount_options
      }
    }
  }


  # lifecycle {
  #   ignore_changes = [
  #     secret,
  #   ]
  # }

  depends_on = [
    azurerm_role_assignment.acr,
    azurerm_container_app_environment_storage.main,
  ]

}

resource "azurerm_container_app_environment_storage" "main" {
  for_each = var.storage

  access_key                   = each.value.access_key
  access_mode                  = each.value.access_mode
  account_name                 = each.value.name
  container_app_environment_id = azurerm_container_app_environment.env.id
  name                         = "${azurerm_container_app_environment.env.name}-${each.key}"
  share_name                   = each.value.share_name
}

resource "azurerm_container_app_custom_domain" "example" {
  name                                     = var.custom_domain
  container_app_id                         = azurerm_container_app.main.id
  container_app_environment_certificate_id = data.azurerm_container_app_environment_certificate.cert["wildcard"].id
  certificate_binding_type                 = "SniEnabled"
}

resource "azurerm_role_assignment" "acr" {
  principal_id         = azurerm_user_assigned_identity.main.principal_id
  role_definition_name = "AcrPull"
  scope                = var.container_registry_id
}

output "id" {
  description = "Container app id"
  value       = azurerm_container_app.main.id
}

output "default_domain" {
  description = "Container app default domain"
  value       = azurerm_container_app_environment.env.default_domain
}

output "endpoint" {
  description = "Container app endpoint"
  value       = azurerm_container_app.main.ingress[0].fqdn
}

output "ip_address" {
  description = "Container app Ingress load balancer IP address"
  value       = azurerm_container_app_environment.env.static_ip_address
}

output "latest_revision" {
  description = "Container app latest revision"
  value       = azurerm_container_app.main.latest_revision_name
}

output "location" {
  description = "Container app environment location"
  value       = azurerm_container_app_environment.env.location
}

output "name" {
  description = "Container app name"
  value       = azurerm_container_app.main.name
}

output "resource_group" {
  description = "Container app resource group name"
  value       = azurerm_container_app.main.resource_group_name
}

output "outbound_ips" {
  description = "Container app outbound ips"
  value       = azurerm_container_app.main.outbound_ip_addresses
}
