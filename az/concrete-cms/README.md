# project

<!-- BEGINNING OF PRE-COMMIT-TERRAFORM DOCS HOOK -->
## Requirements

| Name | Version |
|------|---------|
| <a name="requirement_terraform"></a> [terraform](#requirement\_terraform) | ~> 1.5 |
| <a name="requirement_azurerm"></a> [azurerm](#requirement\_azurerm) | 4.26.0 |

## Providers

| Name | Version |
|------|---------|
| <a name="provider_azurerm"></a> [azurerm](#provider\_azurerm) | 4.26.0 |
| <a name="provider_terraform"></a> [terraform](#provider\_terraform) | n/a |

## Modules

| Name | Source | Version |
|------|--------|---------|
| <a name="module_application_gateway"></a> [application\_gateway](#module\_application\_gateway) | ../modules/app-gateway | n/a |
| <a name="module_container_app"></a> [container\_app](#module\_container\_app) | ../modules/container-app | n/a |
| <a name="module_container_app_private_dns"></a> [container\_app\_private\_dns](#module\_container\_app\_private\_dns) | ../modules/private-dns | n/a |
| <a name="module_key_vault"></a> [key\_vault](#module\_key\_vault) | ../modules/key-vault | n/a |
| <a name="module_key_vault_private-endpoints"></a> [key\_vault\_private-endpoints](#module\_key\_vault\_private-endpoints) | ../modules/private-endpoint | n/a |
| <a name="module_key_vault_resource_group"></a> [key\_vault\_resource\_group](#module\_key\_vault\_resource\_group) | ../modules/resource-group | n/a |
| <a name="module_mysql"></a> [mysql](#module\_mysql) | ../modules/mysql-flexible | n/a |
| <a name="module_mysql_private-endpoints"></a> [mysql\_private-endpoints](#module\_mysql\_private-endpoints) | ../modules/private-endpoint | n/a |
| <a name="module_private_dns_zones"></a> [private\_dns\_zones](#module\_private\_dns\_zones) | ../modules/private-dns | n/a |
| <a name="module_public_ip"></a> [public\_ip](#module\_public\_ip) | ../modules/public-ip | n/a |
| <a name="module_resource_group"></a> [resource\_group](#module\_resource\_group) | ../modules/resource-group | n/a |
| <a name="module_storage_account"></a> [storage\_account](#module\_storage\_account) | ../modules/storage-account | n/a |
| <a name="module_storage_account_private-endpoints"></a> [storage\_account\_private-endpoints](#module\_storage\_account\_private-endpoints) | ../modules/private-endpoint | n/a |
| <a name="module_vnet"></a> [vnet](#module\_vnet) | ../modules/virtual-network | n/a |
| <a name="module_vnet_peerings"></a> [vnet\_peerings](#module\_vnet\_peerings) | ../modules/virtual-network-peering | n/a |

## Resources

| Name | Type |
|------|------|
| [azurerm_key_vault_secret.mysql_password](https://registry.terraform.io/providers/hashicorp/azurerm/4.26.0/docs/data-sources/key_vault_secret) | data source |
| [terraform_remote_state.shared](https://registry.terraform.io/providers/hashicorp/terraform/latest/docs/data-sources/remote_state) | data source |

## Inputs

No inputs.

## Outputs

| Name | Description |
|------|-------------|
| <a name="output_application_gateway_id"></a> [application\_gateway\_id](#output\_application\_gateway\_id) | Application gateway id |
| <a name="output_application_gateway_name"></a> [application\_gateway\_name](#output\_application\_gateway\_name) | Application gateway name |
| <a name="output_container_app_id"></a> [container\_app\_id](#output\_container\_app\_id) | Container app id |
| <a name="output_container_app_name"></a> [container\_app\_name](#output\_container\_app\_name) | Container app name |
| <a name="output_key_vault_id"></a> [key\_vault\_id](#output\_key\_vault\_id) | Key Vault ID |
| <a name="output_key_vault_name"></a> [key\_vault\_name](#output\_key\_vault\_name) | Key Vault name |
| <a name="output_key_vault_resource_group_id"></a> [key\_vault\_resource\_group\_id](#output\_key\_vault\_resource\_group\_id) | Key Vault resource group ID |
| <a name="output_key_vault_resource_group_name"></a> [key\_vault\_resource\_group\_name](#output\_key\_vault\_resource\_group\_name) | Key Vault resource group name |
| <a name="output_mysql_id"></a> [mysql\_id](#output\_mysql\_id) | MySQL Flexible Server ID |
| <a name="output_mysql_name"></a> [mysql\_name](#output\_mysql\_name) | MySQL Flexible Server name |
| <a name="output_private_dns_zone_names"></a> [private\_dns\_zone\_names](#output\_private\_dns\_zone\_names) | Private dns zones created |
| <a name="output_public_ip_id"></a> [public\_ip\_id](#output\_public\_ip\_id) | Public IP ID |
| <a name="output_resource_group_id"></a> [resource\_group\_id](#output\_resource\_group\_id) | Resource group ID |
| <a name="output_resource_group_location"></a> [resource\_group\_location](#output\_resource\_group\_location) | Resource group location |
| <a name="output_resource_group_name"></a> [resource\_group\_name](#output\_resource\_group\_name) | Resource group name |
| <a name="output_storage_account_name"></a> [storage\_account\_name](#output\_storage\_account\_name) | Storage account name |
| <a name="output_subnets"></a> [subnets](#output\_subnets) | List of subnets created |
| <a name="output_virtual_network_peerings_ids"></a> [virtual\_network\_peerings\_ids](#output\_virtual\_network\_peerings\_ids) | List of Virtual Network peerings ids |
| <a name="output_virtual_network_peerings_names"></a> [virtual\_network\_peerings\_names](#output\_virtual\_network\_peerings\_names) | List of Virtual Network peerings names |
| <a name="output_virtual_networks_ids"></a> [virtual\_networks\_ids](#output\_virtual\_networks\_ids) | List of virtual networks IDs |
| <a name="output_virtual_networks_names"></a> [virtual\_networks\_names](#output\_virtual\_networks\_names) | List of virtual networks Names |
<!-- END OF PRE-COMMIT-TERRAFORM DOCS HOOK -->
