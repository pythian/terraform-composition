# key-vault

<!-- BEGINNING OF PRE-COMMIT-TERRAFORM DOCS HOOK -->
## Requirements

No requirements.

## Providers

| Name | Version |
|------|---------|
| <a name="provider_azurerm"></a> [azurerm](#provider\_azurerm) | n/a |

## Modules

No modules.

## Resources

| Name | Type |
|------|------|
| [azurerm_key_vault.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/key_vault) | resource |
| [azurerm_key_vault_secret.secrets](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/key_vault_secret) | resource |
| [azurerm_role_assignment.roles](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/role_assignment) | resource |
| [azurerm_client_config.current](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/data-sources/client_config) | data source |

## Inputs

| Name | Description | Type | Default | Required |
|------|-------------|------|---------|:--------:|
| <a name="input_key_vault_roles"></a> [key\_vault\_roles](#input\_key\_vault\_roles) | Key vault roles assigned to the deployer account | `list(string)` | `[]` | no |
| <a name="input_location"></a> [location](#input\_location) | Location of the key vault to create | `string` | n/a | yes |
| <a name="input_name"></a> [name](#input\_name) | Name of the key vault to create | `string` | n/a | yes |
| <a name="input_public_network_access_enabled"></a> [public\_network\_access\_enabled](#input\_public\_network\_access\_enabled) | Enable public network access to the account | `bool` | `true` | no |
| <a name="input_purge_protection"></a> [purge\_protection](#input\_purge\_protection) | Whether to enable purge protection on the key vault | `string` | `false` | no |
| <a name="input_resource_group"></a> [resource\_group](#input\_resource\_group) | Name of the resource group in which to create the key vault | `string` | n/a | yes |
| <a name="input_retention_days"></a> [retention\_days](#input\_retention\_days) | Days to retain the vault on soft delete before purge | `number` | `30` | no |
| <a name="input_secret_keys"></a> [secret\_keys](#input\_secret\_keys) | Secret keys that map to the sensitive secrets | `set(string)` | `[]` | no |
| <a name="input_secrets"></a> [secrets](#input\_secrets) | Secrets to create in the key vault | `map(string)` | `{}` | no |
| <a name="input_sku"></a> [sku](#input\_sku) | SKU name of the key vault to create | `string` | `"standard"` | no |
| <a name="input_tags"></a> [tags](#input\_tags) | Tags to apply to the key vault | `map(string)` | `{}` | no |
| <a name="input_tenant_id"></a> [tenant\_id](#input\_tenant\_id) | ID of the tenant with which to associate the key vault | `string` | `null` | no |

## Outputs

| Name | Description |
|------|-------------|
| <a name="output_id"></a> [id](#output\_id) | Key vault ID |
| <a name="output_location"></a> [location](#output\_location) | Key vault location |
| <a name="output_name"></a> [name](#output\_name) | Key vault name |
| <a name="output_resource_group"></a> [resource\_group](#output\_resource\_group) | Key vault parent resource group |
| <a name="output_secret_ids"></a> [secret\_ids](#output\_secret\_ids) | Key Vault secrets ids |
| <a name="output_uri"></a> [uri](#output\_uri) | Key vault URI |
<!-- END OF PRE-COMMIT-TERRAFORM DOCS HOOK -->
