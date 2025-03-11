locals {
  env    = yamldecode(file("../env.yaml"))
  inputs = yamldecode(file("./inputs.yaml"))
  az     = fileexists("../local.az.yaml") ? yamldecode(file("../local.az.yaml")) : yamldecode(file("../az.yaml"))
}

terraform {
  required_version = "~> 1.5.0"

  required_providers {
    azurerm = {
      source  = "hashicorp/azurerm"
      version = "4.20.0"
    }
  }
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
