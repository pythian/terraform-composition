variable "allow_remote_gateway_transit" {
  description = "Allow the remote network to use gateways on the local network"
  type        = string

  default = false
}

variable "allow_remote_network_access" {
  description = "Allow the remote network to access hosts on the local network"
  type        = string

  default = false
}

variable "allow_remote_network_forwarding" {
  description = "Allow the remote network to forward traffic to the local network"
  type        = string

  default = false
}

variable "name" {
  description = "Name of the virtual network peering connection"
  type        = string
}

variable "network_name" {
  description = "Name of the local virtual network for the peering connection"
  type        = string
}

variable "remote_network_id" {
  description = "ID of the remote virtual network for the peering connection"
  type        = string
}

variable "resource_group" {
  description = "Name of the resource group for the peering connection"
  type        = string
}

variable "use_remote_gateways" {
  description = "Allow the local network to use gateways on the remote network"
  type        = string

  default = false
}

resource "azurerm_virtual_network_peering" "main" {
  allow_forwarded_traffic      = var.allow_remote_network_forwarding
  allow_gateway_transit        = var.allow_remote_gateway_transit
  allow_virtual_network_access = var.allow_remote_network_access
  name                         = var.name
  remote_virtual_network_id    = var.remote_network_id
  resource_group_name          = var.resource_group
  use_remote_gateways          = var.use_remote_gateways
  virtual_network_name         = var.network_name
}

output "id" {
  description = "The id of the network peering connection"
  value       = azurerm_virtual_network_peering.main.id
}

output "name" {
  description = "The name of the network peering connection"
  value       = azurerm_virtual_network_peering.main.name
}
