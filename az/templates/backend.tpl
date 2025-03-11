# configure azure bucket dynamically.
terraform {
  backend "azurerm" {
    container_name       = "tfstate"
    key                  = "ENVIRONMENT.terraform.tfstate"
    resource_group_name  = "PREFIX-build-PREGION_SHORT-tfstate"
    subscription_id      = "BUILDSUBSCRIPTION"
    storage_account_name = "PREFIXbuildPREGION_SHORTtfstate"
  }
}
