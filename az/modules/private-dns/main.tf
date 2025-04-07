variable "a_records" {
  description = "List of records to create in the private dns zone."
  type = map(object({
    name    = string
    records = list(string)
    ttl     = optional(number, 300)
  }))

  default = {}
}

variable "name" {
  description = "Name of the private dns zone to create. i.e 'privatelink.blob.core.windows.net'"
  type        = string
}

variable "resource_group" {
  description = "Name of the resource group in which to create the private dns zone."
  type        = string
}

variable "vnet_id" {
  description = "Id of the virtual network to link to the private dns zone."
  type        = map(string)

  default = null
}

variable "tags" {
  description = "Tags to apply to the private dns."
  type        = map(string)

  default = {}
}

locals {
  registration_enabled = false
}

resource "azurerm_private_dns_zone" "main" {
  count = var.vnet_id == null ? 0 : 1

  name                = var.name
  resource_group_name = var.resource_group
  tags                = var.tags
}

resource "azurerm_private_dns_zone_virtual_network_link" "link" {
  for_each = var.vnet_id == null ? {} : { for k, v in var.vnet_id : k => v }

  name                  = replace("${var.name}-${basename(each.key)}", ".", "-")
  private_dns_zone_name = azurerm_private_dns_zone.main[0].name
  registration_enabled  = local.registration_enabled
  resource_group_name   = var.resource_group
  tags                  = var.tags
  virtual_network_id    = each.value
}

resource "azurerm_private_dns_a_record" "name" {
  for_each = var.vnet_id == null ? {} : var.a_records

  name                = each.value.name
  records             = each.value.records
  resource_group_name = var.resource_group
  ttl                 = each.value.ttl
  zone_name           = azurerm_private_dns_zone.main[0].name
}

output "id" {
  description = "Private dns zone ID"
  value       = length(azurerm_private_dns_zone.main) > 0 ? azurerm_private_dns_zone.main[0].id : ""
}

output "name" {
  description = "Private dns zone name"
  value       = length(azurerm_private_dns_zone.main) > 0 ? azurerm_private_dns_zone.main[0].name : ""
}

output "resource_group" {
  description = "Private dns zone parent resource group"
  value       = length(azurerm_private_dns_zone.main) > 0 ? azurerm_private_dns_zone.main[0].resource_group_name : ""
}
