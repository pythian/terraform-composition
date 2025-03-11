# private-endpoint

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
| [azurerm_private_endpoint.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/private_endpoint) | resource |

## Inputs

| Name | Description | Type | Default | Required |
|------|-------------|------|---------|:--------:|
| <a name="input_location"></a> [location](#input\_location) | Location of private endpoint to create | `string` | n/a | yes |
| <a name="input_name"></a> [name](#input\_name) | Name of the private endpoint. | `string` | n/a | yes |
| <a name="input_private_dns_zone_id"></a> [private\_dns\_zone\_id](#input\_private\_dns\_zone\_id) | Id of the private dns zone to link to the private endpoint. | `list(string)` | `null` | no |
| <a name="input_private_service_connection"></a> [private\_service\_connection](#input\_private\_service\_connection) | Values to use for the private service connection. | <pre>object({<br/>    name                           = optional(string, null)<br/>    private_connection_resource_id = optional(string, null)<br/>    subresource_names              = optional(list(string), [])<br/>    is_manual_connection           = optional(bool, false)<br/>  })</pre> | n/a | yes |
| <a name="input_resource_group"></a> [resource\_group](#input\_resource\_group) | Name of the resource group in which to create the private endpoint. | `string` | n/a | yes |
| <a name="input_subnet_id"></a> [subnet\_id](#input\_subnet\_id) | Id of the subnet to link to the private endpoint. | `string` | `""` | no |
| <a name="input_tags"></a> [tags](#input\_tags) | Tags to apply to the private endpoint. | `map(string)` | `{}` | no |

## Outputs

| Name | Description |
|------|-------------|
| <a name="output_id"></a> [id](#output\_id) | Private endpoint ID |
| <a name="output_location"></a> [location](#output\_location) | Private endpoint location |
| <a name="output_name"></a> [name](#output\_name) | Private endpoint name |
| <a name="output_resource_group"></a> [resource\_group](#output\_resource\_group) | Private endpoint parent resource group |
<!-- END OF PRE-COMMIT-TERRAFORM DOCS HOOK -->
