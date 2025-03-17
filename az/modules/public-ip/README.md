# public-ip

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
| [azurerm_public_ip.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/public_ip) | resource |

## Inputs

| Name | Description | Type | Default | Required |
|------|-------------|------|---------|:--------:|
| <a name="input_allocation_method"></a> [allocation\_method](#input\_allocation\_method) | allocation method to create the pubic IP. | `string` | `"Static"` | no |
| <a name="input_location"></a> [location](#input\_location) | The location of the public IP to create | `string` | n/a | yes |
| <a name="input_name"></a> [name](#input\_name) | Name of the pubic IP to create. | `string` | n/a | yes |
| <a name="input_resource_group"></a> [resource\_group](#input\_resource\_group) | Name of the resource group in which to create the pubic IP. | `string` | n/a | yes |
| <a name="input_sku"></a> [sku](#input\_sku) | Sku of the public IP to create. | `string` | `"Standard"` | no |
| <a name="input_tags"></a> [tags](#input\_tags) | Tags to apply to the public IP. | `map(string)` | `{}` | no |
| <a name="input_zones"></a> [zones](#input\_zones) | Zones where the public IP to be deployed | `list(string)` | <pre>[<br/>  "1",<br/>  "2",<br/>  "3"<br/>]</pre> | no |

## Outputs

| Name | Description |
|------|-------------|
| <a name="output_id"></a> [id](#output\_id) | The id of the public IP |
| <a name="output_location"></a> [location](#output\_location) | The location of the public IP |
| <a name="output_name"></a> [name](#output\_name) | The name of the public IP |
| <a name="output_resource_group"></a> [resource\_group](#output\_resource\_group) | public IP parent resource group |
<!-- END OF PRE-COMMIT-TERRAFORM DOCS HOOK -->
