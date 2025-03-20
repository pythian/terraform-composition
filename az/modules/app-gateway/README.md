# app-gateway

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
| [azurerm_application_gateway.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/application_gateway) | resource |
| [azurerm_user_assigned_identity.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/user_assigned_identity) | resource |

## Inputs

| Name | Description | Type | Default | Required |
|------|-------------|------|---------|:--------:|
| <a name="input_autoscale_configuration"></a> [autoscale\_configuration](#input\_autoscale\_configuration) | Autoscale settings for the application gateway | <pre>object({<br/>    min_capacity = number<br/>    max_capacity = number<br/>  })</pre> | n/a | yes |
| <a name="input_backend_settings"></a> [backend\_settings](#input\_backend\_settings) | Backend address pools to create | <pre>map(object({<br/>    fqdns = list(string)<br/>    http_setting = optional(object({<br/>      affinity_cookie_name                = optional(string, "ApplicationGatewayAffinity")<br/>      cookie_based_affinity               = optional(string, "Disabled")<br/>      path                                = optional(string, "/")<br/>      port                                = optional(number, 443)<br/>      protocol                            = optional(string, "Https")<br/>      pick_host_name_from_backend_address = optional(bool, true)<br/>      request_timeout                     = optional(number, 20)<br/>    }), {})<br/>  }))</pre> | n/a | yes |
| <a name="input_enable_http2"></a> [enable\_http2](#input\_enable\_http2) | Enables HTTP2 in the application gateway | `bool` | `true` | no |
| <a name="input_frontend_settings"></a> [frontend\_settings](#input\_frontend\_settings) | Frontend address configurations | <pre>map(object({<br/>    hostnames    = list(string)<br/>    port         = string<br/>    protocol     = string<br/>    public_ip_id = string<br/>    require_sni  = optional(bool, false)<br/>  }))</pre> | n/a | yes |
| <a name="input_location"></a> [location](#input\_location) | The location of the application gateway to create | `string` | n/a | yes |
| <a name="input_name"></a> [name](#input\_name) | Name of the application gateway to create | `string` | n/a | yes |
| <a name="input_resource_group"></a> [resource\_group](#input\_resource\_group) | Name of the resource group in which to deploy the application gateway | `string` | n/a | yes |
| <a name="input_routing_rules"></a> [routing\_rules](#input\_routing\_rules) | Defines the routing rules to be added between backend and frontend configurations | <pre>map(object({<br/>    backend_address_pool  = string<br/>    backend_http_settings = string<br/>    http_listener         = string<br/>    priority              = optional(number, null)<br/>    rule_type             = optional(string, "Basic")<br/>  }))</pre> | n/a | yes |
| <a name="input_sku"></a> [sku](#input\_sku) | Sku for the application gateway | `string` | `"Standard_v2"` | no |
| <a name="input_ssl_configuration"></a> [ssl\_configuration](#input\_ssl\_configuration) | ssl configuration for the application gateway | <pre>object({<br/>    ssl_certificate_name = string<br/>    ssl_certificate_path = optional(string, null)<br/>    key_vault_secret_id  = optional(string, null)<br/>  })</pre> | n/a | yes |
| <a name="input_subnet_id"></a> [subnet\_id](#input\_subnet\_id) | ID of the subnet to attach the application gateway | `string` | n/a | yes |
| <a name="input_tags"></a> [tags](#input\_tags) | Tags to associate with the application gateway | `map(string)` | `{}` | no |
| <a name="input_zones"></a> [zones](#input\_zones) | Zones where the application gateway is deployed | `list(string)` | <pre>[<br/>  "1",<br/>  "2",<br/>  "3"<br/>]</pre> | no |

## Outputs

| Name | Description |
|------|-------------|
| <a name="output_id"></a> [id](#output\_id) | Application Gateway ID |
| <a name="output_location"></a> [location](#output\_location) | Application Gateway location |
| <a name="output_name"></a> [name](#output\_name) | Application Gateway name |
| <a name="output_resource_group"></a> [resource\_group](#output\_resource\_group) | Application Gateway parent resource group |
<!-- END OF PRE-COMMIT-TERRAFORM DOCS HOOK -->
