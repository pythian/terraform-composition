# container-registry

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
| [azurerm_container_registry.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/container_registry) | resource |
| [azurerm_role_assignment.push_pull](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/role_assignment) | resource |
| [azurerm_client_config.current](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/data-sources/client_config) | data source |

## Inputs

| Name | Description | Type | Default | Required |
|------|-------------|------|---------|:--------:|
| <a name="input_location"></a> [location](#input\_location) | Location of the Container Registry to create | `string` | n/a | yes |
| <a name="input_name"></a> [name](#input\_name) | Name of the Container Registry to create | `string` | n/a | yes |
| <a name="input_public_network_access_enabled"></a> [public\_network\_access\_enabled](#input\_public\_network\_access\_enabled) | Enable public network access to the account | `bool` | `true` | no |
| <a name="input_resource_group"></a> [resource\_group](#input\_resource\_group) | Name of the resource group in which to create the Container Registry | `string` | n/a | yes |
| <a name="input_sku"></a> [sku](#input\_sku) | SKU name for the Container Registry | `string` | n/a | yes |
| <a name="input_tags"></a> [tags](#input\_tags) | Tags to apply to the Container Registry | `map(string)` | `{}` | no |

## Outputs

| Name | Description |
|------|-------------|
| <a name="output_id"></a> [id](#output\_id) | Container Registry ID |
| <a name="output_location"></a> [location](#output\_location) | Container Registry location |
| <a name="output_login_server"></a> [login\_server](#output\_login\_server) | Container Registry login server |
| <a name="output_name"></a> [name](#output\_name) | Container Registry name |
| <a name="output_resource_group"></a> [resource\_group](#output\_resource\_group) | Container registry parent Resource group |
<!-- END OF PRE-COMMIT-TERRAFORM DOCS HOOK -->
