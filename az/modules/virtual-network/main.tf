variable "address_space" {
  description = "The address space used by the virtual network"
  type        = list(string)
}

variable "dns_servers" {
  description = "The DNS servers to use (defaults to Azure DNS)"
  type        = list(string)

  default = []
}

variable "location" {
  description = "The location of the virtual network to create"
  type        = string
}

variable "name" {
  description = "Name of the virtual network to create"
  type        = string
}

variable "resource_group" {
  description = "Name of the resource group in which to deploy the virtual network"
  type        = string
}

variable "subnets" {
  description = "Map of subnet names and address spaces"
  type        = map(string)

  default = {}
}

variable "subnet_delegations" {
  description = "A map of subnet name to delegation block for the subnet"
  type        = map(map(any))

  default = {}
}

variable "subnet_private_endpoint_policies_enabled" {
  description = "Map of subnet names and service endpoint policies (defaults to Disabled)"
  type        = map(string)

  default = {}
}

variable "subnet_private_service_policies_enabled" {
  description = "Map of subnet names and service link policies (defaults to false)"
  type        = map(string)

  default = {}
}

variable "subnet_services" {
  description = "Map of subnet names and service endpoints"
  type        = map(set(string))

  default = {}
}

variable "tags" {
  description = "Tags to associate with the virtual network"
  type        = map(string)

  default = {}
}

resource "azurerm_virtual_network" "main" {
  address_space       = var.address_space
  dns_servers         = var.dns_servers
  location            = var.location
  name                = var.name
  resource_group_name = var.resource_group
  tags                = var.tags
}

resource "azurerm_subnet" "main" {
  for_each = var.subnets

  address_prefixes                              = [each.value]
  name                                          = each.key
  resource_group_name                           = var.resource_group
  virtual_network_name                          = azurerm_virtual_network.main.name
  private_endpoint_network_policies             = lookup(var.subnet_private_endpoint_policies_enabled, each.key, "Disabled")
  private_link_service_network_policies_enabled = lookup(var.subnet_private_service_policies_enabled, each.key, false)
  service_endpoints                             = lookup(var.subnet_services, each.key, null)

  dynamic "delegation" {
    for_each = lookup(var.subnet_delegations, each.key, {})

    content {
      name = delegation.key

      service_delegation {
        name    = lookup(delegation.value, "service_name")
        actions = lookup(delegation.value, "service_actions", [])
      }
    }
  }
}

output "address_space" {
  description = "The address space of the virtual network"
  value       = azurerm_virtual_network.main.address_space
}

output "id" {
  description = "The id of the virtual network"
  value       = azurerm_virtual_network.main.id
}

output "location" {
  description = "The location of the virtual network"
  value       = azurerm_virtual_network.main.location
}

output "name" {
  description = "The name of the virtual network"
  value       = azurerm_virtual_network.main.name
}

output "resource_group" {
  description = "Virtual network parent resource group"
  value       = azurerm_virtual_network.main.resource_group_name
}

output "subnet_ids" {
  description = "A map of subnet ids associated with the virtual network"
  value       = { for k, s in azurerm_subnet.main : k => s.id }
}

output "subnet_ids_list" {
  description = "A list of subnet ids associated with the virtual network"
  value       = [for s in azurerm_subnet.main : s.id]
}
