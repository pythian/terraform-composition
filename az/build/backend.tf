# configure azure bucket dynamically.
terraform {
  backend "azurerm" {
    container_name       = "tfstate"
    key                  = "build.terraform.tfstate"
    resource_group_name  = "cnx-build-cus-tfstate"
    subscription_id      = "9712bfef-07af-4a61-804e-b2fa08462f70"
    storage_account_name = "cnxbuildcustfstate"
  }
}
