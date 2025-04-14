variable "location" {
  description = "Location of the Container Registry to create"
  type        = string
}

variable "name" {
  description = "Name of the Container Registry to create"
  type        = string
}

variable "public_network_access_enabled" {
  description = "Enable public network access to the account"
  type        = bool

  default = true
}

variable "resource_group" {
  description = "Name of the resource group in which to create the Container Registry"
  type        = string
}

variable "sku" {
  description = "SKU name for the Container Registry"
  type        = string
}

variable "tags" {
  description = "Tags to apply to the Container Registry"
  type        = map(string)

  default = {}
}

resource "azurerm_container_registry" "main" {
  location                      = var.location
  name                          = var.name
  public_network_access_enabled = var.public_network_access_enabled
  resource_group_name           = var.resource_group
  sku                           = var.sku
  tags                          = var.tags
}

output "id" {
  description = "Container Registry ID"
  value       = azurerm_container_registry.main.id
}

output "location" {
  description = "Container Registry location"
  value       = azurerm_container_registry.main.location
}

output "login_server" {
  description = "Container Registry login server"
  value       = azurerm_container_registry.main.login_server
}

output "name" {
  description = "Container Registry name"
  value       = azurerm_container_registry.main.name
}

output "resource_group" {
  description = "Container registry parent Resource group"
  value       = azurerm_container_registry.main.resource_group_name
}
