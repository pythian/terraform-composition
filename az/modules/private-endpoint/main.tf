variable "location" {
  description = "Location of private endpoint to create"
  type        = string
}

variable "name" {
  description = "Name of the private endpoint."
  type        = string
}

variable "private_dns_zone_id" {
  description = "Id of the private dns zone to link to the private endpoint."
  type        = list(string)

  default = null
}

variable "private_service_connection" {
  description = "Values to use for the private service connection."
  type = object({
    name                           = optional(string, null)
    private_connection_resource_id = optional(string, null)
    subresource_names              = optional(list(string), [])
    is_manual_connection           = optional(bool, false)
  })
}

variable "resource_group" {
  description = "Name of the resource group in which to create the private endpoint."
  type        = string
}

variable "subnet_id" {
  description = "Id of the subnet to link to the private endpoint."
  type        = string

  default = ""
}

variable "tags" {
  description = "Tags to apply to the private endpoint."
  type        = map(string)

  default = {}
}

resource "azurerm_private_endpoint" "main" {
  count               = var.subnet_id == "" ? 0 : 1
  location            = var.location
  name                = var.name
  resource_group_name = var.resource_group
  subnet_id           = var.subnet_id

  private_dns_zone_group {
    name                 = var.name
    private_dns_zone_ids = var.private_dns_zone_id
  }

  private_service_connection {
    name                           = var.private_service_connection.name
    private_connection_resource_id = var.private_service_connection.private_connection_resource_id
    subresource_names              = var.private_service_connection.subresource_names
    is_manual_connection           = var.private_service_connection.is_manual_connection
  }
}

output "id" {
  description = "Private endpoint ID"
  value       = length(azurerm_private_endpoint.main) > 0 ? azurerm_private_endpoint.main[0].id : null
}

output "location" {
  description = "Private endpoint location"
  value       = length(azurerm_private_endpoint.main) > 0 ? azurerm_private_endpoint.main[0].location : null
}

output "name" {
  description = "Private endpoint name"
  value       = length(azurerm_private_endpoint.main) > 0 ? azurerm_private_endpoint.main[0].name : null
}

output "resource_group" {
  description = "Private endpoint parent resource group"
  value       = length(azurerm_private_endpoint.main) > 0 ? azurerm_private_endpoint.main[0].resource_group_name : null
}
