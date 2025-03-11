locals {
  env      = yamldecode(file("../env.yaml"))
  inputs   = yamldecode(file("./inputs.yaml"))
  az       = fileexists("../local.az.yaml") ? yamldecode(file("../local.az.yaml")) : yamldecode(file("../az.yaml"))
  versions = yamldecode(file("../versions.yaml"))
}

provider "azurerm" {
  subscription_id = local.env.subscription
  features {}
}

module "resource_group" {
  source = "../modules/resource-group"

  location = local.env.location
  name = coalesce(local.inputs.resource_group_name_override,
    format("%s-%s-%s-%s",
      local.az.prefix,
      local.env.environment,
      local.env.location_short,
      local.inputs.resource_group_name,
    )
  )
  tags = merge(local.inputs.tags, local.env.tags)
}

module "storage_account" {
  source = "../modules/storage-account"

  containers = local.inputs.containers
  location   = local.env.location
  name = coalesce(local.inputs.resource_group_name_override,
    format("%s%s%s%s",
      local.az.prefix,
      local.env.environment,
      local.env.location_short,
      local.inputs.storage_account_name,
    )
  )
  resource_group = module.resource_group.name
  tags           = merge(local.inputs.tags, local.env.tags)
}

output "resource_group_id" {
  description = "Resource group ID"
  value       = module.resource_group.id
}

output "resource_group_location" {
  description = "Resource group location"
  value       = module.resource_group.location
}

output "resource_group_name" {
  description = "Resource group name"
  value       = module.resource_group.name
}

output "storage_account_id" {
  description = "Storage account ID"
  value       = module.storage_account.id
}

output "storage_account_location" {
  description = "Storage account location"
  value       = module.storage_account.location
}

output "storage_account_name" {
  description = "Storage account name"
  value       = module.storage_account.name
}
