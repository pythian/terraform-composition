variable "containers" {
  description = "List of containers to create in the storage account"
  type        = set(string)

  default = []
}

variable "hierarchical_namespace_enabled" {
  description = "Whether the storage account has hierarchical namespaces enabled"
  type        = bool

  default = false
}

variable "kind" {
  description = "Type of storage account to create (BlobStorage, BlockBlobStorage, FileStorage, Storage, StorageV2)"
  type        = string

  default = "StorageV2"
}

variable "location" {
  description = "Location of the storage account to create"
  type        = string
}

variable "name" {
  description = "Name of the storage account to create"
  type        = string
}

variable "public_network_access_enabled" {
  description = "Whether or not public network access is allowed for the storage account. When false, only private endpoints can access the storage account."
  type        = bool

  default = true
}

variable "replication_type" {
  description = "Type of replication to use (LRS, GRS, RAGRS, ZRS, GZRS, RAGZRS)"
  type        = string

  default = "LRS"
}

variable "resource_group" {
  description = "Name of the resource group in which to create the storage account"
  type        = string
}

variable "shares" {
  description = "List of shares to create in the storage account"
  type = map(object({
    name             = string
    enabled_protocol = string
    quota            = number
    acl = map(object({
      id          = string
      permissions = string
    }))
  }))

  default = {}

}

variable "tags" {
  description = "Tags to apply to the storage account"
  type        = map(string)

  default = {}
}

variable "tier" {
  description = "Account tier for the resource (Standard, Premium)"
  type        = string

  default = "Standard"
}

resource "azurerm_storage_account" "main" {
  account_kind                  = var.kind
  account_replication_type      = var.replication_type
  account_tier                  = var.tier
  is_hns_enabled                = var.hierarchical_namespace_enabled
  location                      = var.location
  name                          = var.name
  public_network_access_enabled = var.public_network_access_enabled
  resource_group_name           = var.resource_group
  tags                          = var.tags
}

resource "azurerm_storage_container" "main" {
  for_each           = var.containers
  name               = each.value
  storage_account_id = azurerm_storage_account.main.id
}

resource "azurerm_storage_share" "main" {
  for_each           = var.shares
  name               = each.value.name
  storage_account_id = azurerm_storage_account.main.id
  enabled_protocol   = each.value.enabled_protocol
  quota              = each.value.quota

  dynamic "acl" {
    for_each = each.value.acl
    content {
      id = acl.value.id
      access_policy {
        permissions = acl.value.permissions
      }
    }
  }
}

resource "azurerm_storage_data_lake_gen2_filesystem" "main" {
  count = var.hierarchical_namespace_enabled ? 1 : 0

  name               = format("%s-filesystem", var.name)
  storage_account_id = azurerm_storage_account.main.id
}

output "access_key" {
  description = "Storage account access key"
  value       = azurerm_storage_account.main.primary_access_key
}

output "container_ids" {
  description = "Containers created under the storage account"
  value       = [for c in azurerm_storage_container.main : c.id]
}

output "container_names" {
  description = "Containers created under the storage account"
  value       = [for c in azurerm_storage_container.main : c.name]
}

output "storage_data_lake_filesystem_id" {
  description = "Data lake filesystem created under the storage account"
  value       = var.hierarchical_namespace_enabled ? azurerm_storage_data_lake_gen2_filesystem.main[0].id : null
}

output "hierarchical_namespaces_enabled" {
  description = "Whether hierarchical namespaces are enabled"
  value       = azurerm_storage_account.main.is_hns_enabled
}

output "id" {
  description = "Storage account ID"
  value       = azurerm_storage_account.main.id
}

output "kind" {
  description = "Storage account kind"
  value       = azurerm_storage_account.main.account_kind
}

output "location" {
  description = "Storage account location"
  value       = azurerm_storage_account.main.location
}

output "name" {
  description = "Storage account name"
  value       = azurerm_storage_account.main.name
}

output "primary_blob_endpoint" {
  description = "Storage account primary blob endpoint"
  value       = azurerm_storage_account.main.primary_blob_endpoint
}

output "primary_queue_endpoint" {
  description = "Storage account primary queue endpoint"
  value       = azurerm_storage_account.main.primary_queue_endpoint
}

output "replication_type" {
  description = "Storage account replication type"
  value       = azurerm_storage_account.main.account_replication_type
}

output "resource_group" {
  description = "Storage account parent resource group"
  value       = azurerm_storage_account.main.resource_group_name
}

output "share_ids" {
  description = "Shares created under the storage account"
  value       = [for s in azurerm_storage_share.main : s.id]
}

output "share_names" {
  description = "Shares created under the storage account"
  value       = [for s in azurerm_storage_share.main : s.name]
}

output "tier" {
  description = "Storage account tier"
  value       = azurerm_storage_account.main.account_tier
}
