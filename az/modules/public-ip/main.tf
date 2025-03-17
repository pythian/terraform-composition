variable "allocation_method" {
  description = "allocation method to create the pubic IP."
  type        = string

  default = "Static"
}

variable "location" {
  description = "The location of the public IP to create"
  type        = string
}

variable "name" {
  description = "Name of the pubic IP to create."
  type        = string
}

variable "resource_group" {
  description = "Name of the resource group in which to create the pubic IP."
  type        = string
}

variable "sku" {
  description = "Sku of the public IP to create."
  type        = string

  default = "Standard"
}

variable "tags" {
  description = "Tags to apply to the public IP."
  type        = map(string)

  default = {}
}

variable "zones" {
  description = "Zones where the public IP to be deployed"
  type        = list(string)

  default = ["1", "2", "3"]
}

locals {
  ip_tags = {}
}

resource "azurerm_public_ip" "main" {
  allocation_method   = var.allocation_method
  location            = var.location
  ip_tags             = local.ip_tags
  name                = var.name
  resource_group_name = var.resource_group
  sku                 = var.sku
  tags                = var.tags
  zones               = var.zones
}

output "id" {
  description = "The id of the public IP"
  value       = azurerm_public_ip.main.id
}

output "location" {
  description = "The location of the public IP"
  value       = azurerm_public_ip.main.location
}

output "name" {
  description = "The name of the public IP"
  value       = azurerm_public_ip.main.name
}

output "resource_group" {
  description = "public IP parent resource group"
  value       = azurerm_public_ip.main.resource_group_name
}
