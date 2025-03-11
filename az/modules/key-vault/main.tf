variable "key_vault_roles" {
  description = "Key vault roles assigned to the deployer account"
  type        = list(string)

  default = []
}

variable "location" {
  description = "Location of the key vault to create"
  type        = string
}

variable "name" {
  description = "Name of the key vault to create"
  type        = string
}

variable "public_network_access_enabled" {
  description = "Enable public network access to the account"
  type        = bool

  default = true
}

variable "purge_protection" {
  description = "Whether to enable purge protection on the key vault"
  type        = string

  default = false
}

variable "resource_group" {
  description = "Name of the resource group in which to create the key vault"
  type        = string
}

variable "retention_days" {
  description = "Days to retain the vault on soft delete before purge"
  type        = number

  default = 30
}

variable "secret_keys" {
  description = "Secret keys that map to the sensitive secrets"
  type        = set(string)

  default = []
}

variable "secrets" {
  description = "Secrets to create in the key vault"
  type        = map(string)

  default   = {}
  sensitive = true
}

variable "sku" {
  description = "SKU name of the key vault to create"
  type        = string

  default = "standard"
}

variable "tags" {
  description = "Tags to apply to the key vault"
  type        = map(string)

  default = {}
}

variable "tenant_id" {
  description = "ID of the tenant with which to associate the key vault"
  type        = string

  default = null
}

locals {
  enable_rbac_authorization = true
  key_vault_roles = { for role in var.key_vault_roles : "${role}" => {
    role_definition_name = role
    }
  }
}

data "azurerm_client_config" "current" {}

resource "azurerm_key_vault" "main" {
  enable_rbac_authorization     = local.enable_rbac_authorization
  location                      = var.location
  name                          = var.name
  public_network_access_enabled = var.public_network_access_enabled
  purge_protection_enabled      = var.purge_protection
  resource_group_name           = var.resource_group
  sku_name                      = var.sku
  soft_delete_retention_days    = var.retention_days
  tenant_id                     = coalesce(var.tenant_id, data.azurerm_client_config.current.tenant_id)
}

resource "azurerm_role_assignment" "roles" {
  for_each = local.key_vault_roles

  principal_id         = data.azurerm_client_config.current.object_id
  role_definition_name = each.value.role_definition_name
  scope                = azurerm_key_vault.main.id
}

resource "azurerm_key_vault_secret" "secrets" {
  for_each = var.secret_keys

  key_vault_id = azurerm_key_vault.main.id
  name         = each.key
  value        = var.secrets[each.key]
}

output "id" {
  description = "Key vault ID"
  value       = azurerm_key_vault.main.id
}

output "location" {
  description = "Key vault location"
  value       = azurerm_key_vault.main.location
}

output "name" {
  description = "Key vault name"
  value       = azurerm_key_vault.main.name
}

output "resource_group" {
  description = "Key vault parent resource group"
  value       = azurerm_key_vault.main.resource_group_name
}

output "uri" {
  description = "Key vault URI"
  value       = azurerm_key_vault.main.vault_uri
}
