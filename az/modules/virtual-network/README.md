# virtual-network

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
| [azurerm_subnet.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/subnet) | resource |
| [azurerm_virtual_network.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/virtual_network) | resource |

## Inputs

| Name | Description | Type | Default | Required |
|------|-------------|------|---------|:--------:|
| <a name="input_address_space"></a> [address\_space](#input\_address\_space) | The address space used by the virtual network | `list(string)` | n/a | yes |
| <a name="input_dns_servers"></a> [dns\_servers](#input\_dns\_servers) | The DNS servers to use (defaults to Azure DNS) | `list(string)` | `[]` | no |
| <a name="input_location"></a> [location](#input\_location) | The location of the virtual network to create | `string` | n/a | yes |
| <a name="input_name"></a> [name](#input\_name) | Name of the virtual network to create | `string` | n/a | yes |
| <a name="input_resource_group"></a> [resource\_group](#input\_resource\_group) | Name of the resource group in which to deploy the virtual network | `string` | n/a | yes |
| <a name="input_subnet_delegations"></a> [subnet\_delegations](#input\_subnet\_delegations) | A map of subnet name to delegation block for the subnet | `map(map(any))` | `{}` | no |
| <a name="input_subnet_private_endpoint_policies_enabled"></a> [subnet\_private\_endpoint\_policies\_enabled](#input\_subnet\_private\_endpoint\_policies\_enabled) | Map of subnet names and service endpoint policies (defaults to Disabled) | `map(string)` | `{}` | no |
| <a name="input_subnet_private_service_policies_enabled"></a> [subnet\_private\_service\_policies\_enabled](#input\_subnet\_private\_service\_policies\_enabled) | Map of subnet names and service link policies (defaults to false) | `map(string)` | `{}` | no |
| <a name="input_subnet_services"></a> [subnet\_services](#input\_subnet\_services) | Map of subnet names and service endpoints | `map(set(string))` | `{}` | no |
| <a name="input_subnets"></a> [subnets](#input\_subnets) | Map of subnet names and address spaces | `map(string)` | `{}` | no |
| <a name="input_tags"></a> [tags](#input\_tags) | Tags to associate with the virtual network | `map(string)` | `{}` | no |

## Outputs

| Name | Description |
|------|-------------|
| <a name="output_address_space"></a> [address\_space](#output\_address\_space) | The address space of the virtual network |
| <a name="output_id"></a> [id](#output\_id) | The id of the virtual network |
| <a name="output_location"></a> [location](#output\_location) | The location of the virtual network |
| <a name="output_name"></a> [name](#output\_name) | The name of the virtual network |
| <a name="output_resource_group"></a> [resource\_group](#output\_resource\_group) | Virtual network parent resource group |
| <a name="output_subnet_ids"></a> [subnet\_ids](#output\_subnet\_ids) | A list of subnet ids associated with the virtual network |
<!-- END OF PRE-COMMIT-TERRAFORM DOCS HOOK -->
