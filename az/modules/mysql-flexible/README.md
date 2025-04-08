# mysql-flexible

<!-- BEGINNING OF PRE-COMMIT-TERRAFORM DOCS HOOK -->
## Requirements

No requirements.

## Providers

| Name | Version |
|------|---------|
| <a name="provider_azurerm"></a> [azurerm](#provider\_azurerm) | n/a |
| <a name="provider_random"></a> [random](#provider\_random) | n/a |

## Modules

No modules.

## Resources

| Name | Type |
|------|------|
| [azurerm_key_vault_secret.password](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/key_vault_secret) | resource |
| [azurerm_mysql_flexible_database.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/mysql_flexible_database) | resource |
| [azurerm_mysql_flexible_server.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/mysql_flexible_server) | resource |
| [azurerm_mysql_flexible_server_configuration.configuration](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/mysql_flexible_server_configuration) | resource |
| [random_password.main](https://registry.terraform.io/providers/hashicorp/random/latest/docs/resources/password) | resource |

## Inputs

| Name | Description | Type | Default | Required |
|------|-------------|------|---------|:--------:|
| <a name="input_administrator_login"></a> [administrator\_login](#input\_administrator\_login) | Administrator login of the MySQL Flexible Server | `string` | n/a | yes |
| <a name="input_backup_retention_days"></a> [backup\_retention\_days](#input\_backup\_retention\_days) | Backup configuration of the MySQL Flexible Server | `number` | `7` | no |
| <a name="input_databases"></a> [databases](#input\_databases) | Databases to be created on the MySQL Flexible Server | <pre>map(object({<br/>    charset   = optional(string, "utf8")<br/>    collation = optional(string, "utf8_unicode_ci")<br/>  }))</pre> | n/a | yes |
| <a name="input_delegated_subnet_id"></a> [delegated\_subnet\_id](#input\_delegated\_subnet\_id) | Delegated Subnet ID of the MySQL Flexible Server | `string` | `null` | no |
| <a name="input_key_vault_id"></a> [key\_vault\_id](#input\_key\_vault\_id) | Key vault ID to save the secret | `string` | `""` | no |
| <a name="input_location"></a> [location](#input\_location) | Location of the MySQL Flexible Server to create | `string` | n/a | yes |
| <a name="input_name"></a> [name](#input\_name) | Name of the MySQL Flexible Server to create | `string` | n/a | yes |
| <a name="input_private_dns_zone_id"></a> [private\_dns\_zone\_id](#input\_private\_dns\_zone\_id) | Private DNS zone to be used by the MySQL Flexible Server | `string` | `null` | no |
| <a name="input_resource_group"></a> [resource\_group](#input\_resource\_group) | Name of the resource group in which to create the MySQL Flexible Server | `string` | n/a | yes |
| <a name="input_server_configuration"></a> [server\_configuration](#input\_server\_configuration) | map of configurations to be applied | `map(string)` | `{}` | no |
| <a name="input_sku"></a> [sku](#input\_sku) | SKU name of the MySQL Flexible Server to create | `string` | `"GP_Standard_D2ads_v5"` | no |
| <a name="input_tags"></a> [tags](#input\_tags) | Tags to be used by MySQL Flexible Server | `map(string)` | `{}` | no |
| <a name="input_zone"></a> [zone](#input\_zone) | Zone to be used by MySQL Flexible Server | `string` | `"1"` | no |

## Outputs

| Name | Description |
|------|-------------|
| <a name="output_administrator_password"></a> [administrator\_password](#output\_administrator\_password) | Mysql Server administrator password |
| <a name="output_database_ids"></a> [database\_ids](#output\_database\_ids) | Mysql Server databases created |
| <a name="output_database_names"></a> [database\_names](#output\_database\_names) | Mysql Server databases created |
| <a name="output_id"></a> [id](#output\_id) | Mysql Server ID |
| <a name="output_location"></a> [location](#output\_location) | Mysql Server location |
| <a name="output_name"></a> [name](#output\_name) | Mysql Server name |
| <a name="output_resource_group"></a> [resource\_group](#output\_resource\_group) | Mysql Server parent resource group |
<!-- END OF PRE-COMMIT-TERRAFORM DOCS HOOK -->
