# project

<!-- BEGINNING OF PRE-COMMIT-TERRAFORM DOCS HOOK -->
## Requirements

| Name | Version |
|------|---------|
| <a name="requirement_terraform"></a> [terraform](#requirement\_terraform) | ~> 1.5 |
| <a name="requirement_azurerm"></a> [azurerm](#requirement\_azurerm) | 4.20.0 |

## Providers

No providers.

## Modules

| Name | Source | Version |
|------|--------|---------|
| <a name="module_app_service"></a> [app\_service](#module\_app\_service) | ../modules/app-service | n/a |
| <a name="module_app_service_private-endpoints"></a> [app\_service\_private-endpoints](#module\_app\_service\_private-endpoints) | ../modules/private-endpoint | n/a |
| <a name="module_container_registry"></a> [container\_registry](#module\_container\_registry) | ../modules/container-registry | n/a |
| <a name="module_key_vault"></a> [key\_vault](#module\_key\_vault) | ../modules/key-vault | n/a |
| <a name="module_key_vault_private-endpoints"></a> [key\_vault\_private-endpoints](#module\_key\_vault\_private-endpoints) | ../modules/private-endpoint | n/a |
| <a name="module_key_vault_resource_group"></a> [key\_vault\_resource\_group](#module\_key\_vault\_resource\_group) | ../modules/resource-group | n/a |
| <a name="module_mysql"></a> [mysql](#module\_mysql) | ../modules/mysql-flexible | n/a |
| <a name="module_mysql_private-endpoints"></a> [mysql\_private-endpoints](#module\_mysql\_private-endpoints) | ../modules/private-endpoint | n/a |
| <a name="module_private_dns_zones"></a> [private\_dns\_zones](#module\_private\_dns\_zones) | ../modules/private-dns | n/a |
| <a name="module_public_ip"></a> [public\_ip](#module\_public\_ip) | ../modules/public-ip | n/a |
| <a name="module_resource_group"></a> [resource\_group](#module\_resource\_group) | ../modules/resource-group | n/a |
| <a name="module_vnet"></a> [vnet](#module\_vnet) | ../modules/virtual-network | n/a |
| <a name="module_vnet_peerings"></a> [vnet\_peerings](#module\_vnet\_peerings) | ../modules/virtual-network-peering | n/a |

## Resources

No resources.

## Inputs

No inputs.

## Outputs

| Name | Description |
|------|-------------|
| <a name="output_app_service_plan_id"></a> [app\_service\_plan\_id](#output\_app\_service\_plan\_id) | App service plan id |
| <a name="output_app_service_plan_name"></a> [app\_service\_plan\_name](#output\_app\_service\_plan\_name) | App service plan name |
| <a name="output_app_service_webapp_ids"></a> [app\_service\_webapp\_ids](#output\_app\_service\_webapp\_ids) | App service plan deployed web applications ids |
| <a name="output_app_service_webapp_names"></a> [app\_service\_webapp\_names](#output\_app\_service\_webapp\_names) | App service plan deployed web applications names |
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
| <a name="output_subnets"></a> [subnets](#output\_subnets) | List of subnets created |
| <a name="output_virtual_network_peerings_ids"></a> [virtual\_network\_peerings\_ids](#output\_virtual\_network\_peerings\_ids) | List of Virtual Network peerings ids |
| <a name="output_virtual_network_peerings_names"></a> [virtual\_network\_peerings\_names](#output\_virtual\_network\_peerings\_names) | List of Virtual Network peerings names |
| <a name="output_virtual_networks_ids"></a> [virtual\_networks\_ids](#output\_virtual\_networks\_ids) | List of virtual networks IDs |
| <a name="output_virtual_networks_names"></a> [virtual\_networks\_names](#output\_virtual\_networks\_names) | List of virtual networks Names |
<!-- END OF PRE-COMMIT-TERRAFORM DOCS HOOK -->
