variable "administrator_login" {
  description = "Administrator login of the MySQL Flexible Server "
  type        = string
}

variable "backup_retention_days" {
  description = "Backup configuration of the MySQL Flexible Server"
  type        = number

  default = 7
}

variable "databases" {
  description = "Databases to be created on the MySQL Flexible Server "
  type = map(object({
    charset   = optional(string, "utf8")
    collation = optional(string, "utf8_unicode_ci")
  }))
}

variable "delegated_subnet_id" {
  description = "Delegated Subnet ID of the MySQL Flexible Server"
  type        = string

  default = null
}

variable "key_vault_id" {
  description = "Key vault ID to save the secret"
  type        = string

  default = ""
}

variable "location" {
  description = "Location of the MySQL Flexible Server to create"
  type        = string
}

variable "name" {
  description = "Name of the MySQL Flexible Server to create"
  type        = string
}

variable "private_dns_zone_id" {
  description = "Private DNS zone to be used by the MySQL Flexible Server"
  type        = string

  default = null
}

variable "resource_group" {
  description = "Name of the resource group in which to create the MySQL Flexible Server"
  type        = string
}

variable "server_configuration" {
  description = "map of configurations to be applied"
  type        = map(string)

  default = {}
}

variable "sku" {
  description = "SKU name of the MySQL Flexible Server to create"
  type        = string

  default = "GP_Standard_D2ads_v5"
}

variable "tags" {
  description = "Tags to be used by MySQL Flexible Server"
  type        = map(string)

  default = {}
}

variable "zone" {
  description = "Zone to be used by MySQL Flexible Server"
  type        = string

  default = "1"
}

locals {
  password_length        = 14
  password_special_chars = "*^!"
}

resource "random_password" "main" {
  length           = local.password_length
  override_special = local.password_special_chars
}


resource "azurerm_mysql_flexible_server" "main" {
  administrator_login    = var.administrator_login
  administrator_password = random_password.main.result
  backup_retention_days  = var.backup_retention_days
  delegated_subnet_id    = var.delegated_subnet_id
  location               = var.location
  name                   = var.name
  private_dns_zone_id    = var.private_dns_zone_id
  resource_group_name    = var.resource_group
  sku_name               = var.sku
  tags                   = var.tags
  zone                   = var.zone
}

resource "azurerm_mysql_flexible_database" "main" {
  for_each = var.databases

  name                = each.key
  resource_group_name = azurerm_mysql_flexible_server.main.resource_group_name
  server_name         = azurerm_mysql_flexible_server.main.name
  charset             = each.value.charset
  collation           = each.value.collation
}

resource "azurerm_mysql_flexible_server_configuration" "configuration" {
  for_each = var.server_configuration

  name                = each.key
  resource_group_name = azurerm_mysql_flexible_server.main.resource_group_name
  server_name         = azurerm_mysql_flexible_server.main.name
  value               = each.value
}

resource "azurerm_key_vault_secret" "password" {
  count = var.key_vault_id == "" ? 0 : 1

  key_vault_id = var.key_vault_id
  name         = replace("${azurerm_mysql_flexible_server.main.name}-${azurerm_mysql_flexible_server.main.administrator_login}", "_", "-")
  value        = random_password.main.result
}

output "administrator_password" {
  description = "Mysql Server administrator password"
  value       = azurerm_mysql_flexible_server.main.administrator_password
  sensitive   = true
}

output "id" {
  description = "Mysql Server ID"
  value       = azurerm_mysql_flexible_server.main.id
}

output "location" {
  description = "Mysql Server location"
  value       = azurerm_mysql_flexible_server.main.location
}

output "name" {
  description = "Mysql Server name"
  value       = azurerm_mysql_flexible_server.main.name
}

output "resource_group" {
  description = "Mysql Server parent resource group"
  value       = azurerm_mysql_flexible_server.main.resource_group_name
}

output "database_ids" {
  description = "Mysql Server databases created"
  value       = [for v in azurerm_mysql_flexible_database.main : v.id]
}

output "database_names" {
  description = "Mysql Server databases created"
  value       = [for v in azurerm_mysql_flexible_database.main : v.name]
}
