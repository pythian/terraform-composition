variable "location" {
  description = "Location of the resource group to create"
  type        = string
}

variable "name" {
  description = "Name of the resource group to create"
  type        = string
}

variable "tags" {
  description = "Tags to apply to the resource group"
  type        = map(string)

  default = {}
}

resource "azurerm_resource_group" "main" {
  location = var.location
  name     = var.name
  tags     = var.tags
}

output "id" {
  description = "Resource group ID"
  value       = azurerm_resource_group.main.id
}

output "location" {
  description = "Resource group location"
  value       = azurerm_resource_group.main.location
}

output "name" {
  description = "Resource group name"
  value       = azurerm_resource_group.main.name
}
