# virtual-network-peering

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
| [azurerm_virtual_network_peering.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/virtual_network_peering) | resource |

## Inputs

| Name | Description | Type | Default | Required |
|------|-------------|------|---------|:--------:|
| <a name="input_allow_remote_gateway_transit"></a> [allow\_remote\_gateway\_transit](#input\_allow\_remote\_gateway\_transit) | Allow the remote network to use gateways on the local network | `string` | `false` | no |
| <a name="input_allow_remote_network_access"></a> [allow\_remote\_network\_access](#input\_allow\_remote\_network\_access) | Allow the remote network to access hosts on the local network | `string` | `false` | no |
| <a name="input_allow_remote_network_forwarding"></a> [allow\_remote\_network\_forwarding](#input\_allow\_remote\_network\_forwarding) | Allow the remote network to forward traffic to the local network | `string` | `false` | no |
| <a name="input_name"></a> [name](#input\_name) | Name of the virtual network peering connection | `string` | n/a | yes |
| <a name="input_network_name"></a> [network\_name](#input\_network\_name) | Name of the local virtual network for the peering connection | `string` | n/a | yes |
| <a name="input_remote_network_id"></a> [remote\_network\_id](#input\_remote\_network\_id) | ID of the remote virtual network for the peering connection | `string` | n/a | yes |
| <a name="input_resource_group"></a> [resource\_group](#input\_resource\_group) | Name of the resource group for the peering connection | `string` | n/a | yes |
| <a name="input_use_remote_gateways"></a> [use\_remote\_gateways](#input\_use\_remote\_gateways) | Allow the local network to use gateways on the remote network | `string` | `false` | no |

## Outputs

| Name | Description |
|------|-------------|
| <a name="output_id"></a> [id](#output\_id) | The id of the network peering connection |
| <a name="output_name"></a> [name](#output\_name) | The name of the network peering connection |
<!-- END OF PRE-COMMIT-TERRAFORM DOCS HOOK -->
