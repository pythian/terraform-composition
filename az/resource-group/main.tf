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
  features {}
}

resource "azurerm_resource_group" "main" {
  location = local.inputs.location
  name     = local.inputs.name
  tags     = local.inputs.tags
}

output "id" {
  description = "Resource group ID"
  value       = azurerm_resource_group.main.id
}

output "location" {
  description = "Resource group location"
  value       = azurerm_resource_group.main.location
}

output "name" {
  description = "Resource group name"
  value       = azurerm_resource_group.main.name
}
