variable "location" {
  description = "Location of the App Service Plan to create"
  type        = string
}

variable "name" {
  description = "Name of the App Service Plan to create"
  type        = string
}

variable "resource_group" {
  description = "Name of the resource group in which to create the App Service Plan"
  type        = string
}

variable "sku" {
  description = "SKU name of the App Service Plan to create"
  type        = string

  default = "P0v3"
}

variable "webapps" {
  description = "Web Applications to be deployed in this App Service Plan"
  type        = set(string)
}

variable "site_config" {
  description = "Site configurations for each web app"
  type        = map(map(any))

  default = {}
}

locals {
  os_type = "linux"
}

resource "azurerm_service_plan" "main" {
  name                = var.name
  location            = var.location
  resource_group_name = var.resource_group
  os_type             = local.os_type
  sku_name            = var.sku
}

resource "azurerm_linux_web_app" "main" {
  for_each = var.webapps

  name                = each.key
  resource_group_name = azurerm_service_plan.main.resource_group_name
  location            = azurerm_service_plan.main.location
  service_plan_id     = azurerm_service_plan.main.id

  dynamic "site_config" {
    for_each = lookup(var.site_config, each.key, {})

    content {
      always_on = lookup(site_config.value, "always_on", true)
    }
  }
}

output "id" {
  description = "App Service Plan ID"
  value       = azurerm_app_service_plan.main
}

output "location" {
  description = "App Service Plan location"
  value       = azurerm_app_service_plan.main.location
}

output "name" {
  description = "App Service Plan name"
  value       = azurerm_app_service_plan.main.name
}

output "resource_group" {
  description = "App Service Plan parent resource group"
  value       = azurerm_app_service_plan.main.resource_group_name
}

output "webapp_names" {
  description = "Web Applications deployed names"
  value       = [for w in azurermazurerm_linux_web_app.main : w.name]
}

output "webapp_ids" {
  description = "Web Applications deployed ids"
  value       = [for w in azurermazurerm_linux_web_app.main : w.id]
}
