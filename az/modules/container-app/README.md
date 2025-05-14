# container-app

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
| [azurerm_container_app.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/container_app) | resource |
| [azurerm_container_app_custom_domain.example](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/container_app_custom_domain) | resource |
| [azurerm_container_app_environment.env](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/container_app_environment) | resource |
| [azurerm_container_app_environment_storage.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/container_app_environment_storage) | resource |
| [azurerm_role_assignment.acr](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/role_assignment) | resource |
| [azurerm_user_assigned_identity.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/user_assigned_identity) | resource |
| [azurerm_container_app_environment_certificate.cert](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/data-sources/container_app_environment_certificate) | data source |

## Inputs

| Name | Description | Type | Default | Required |
|------|-------------|------|---------|:--------:|
| <a name="input_container"></a> [container](#input\_container) | container definitions to use for the container app | <pre>object({<br/>    args    = optional(list(string), null)<br/>    command = optional(list(string), null)<br/>    cpu     = optional(number, null)<br/>    custom_scale_rule = optional(map(object({<br/>      name     = string<br/>      type     = string<br/>      metadata = map(string)<br/>    })), {})<br/>    http_scale_rule = optional(object({<br/>      name                = string<br/>      concurrent_requests = number<br/>    }), null)<br/>    env = optional(list(object({<br/>      name        = string<br/>      secret_name = optional(string, null)<br/>      value       = optional(string, null)<br/>    })), [])<br/>    image = string<br/>    liveness_probe = optional(object({<br/>      failure_threshold = optional(number, null)<br/>      http_get = optional(object({<br/>        path      = optional(string, null)<br/>        port      = number<br/>        transport = optional(string, "HTTP")<br/>      }), null)<br/>      initial_delay_seconds = optional(number, null)<br/>      interval_seconds      = optional(number, null)<br/>      timeout_seconds       = optional(number, null)<br/>    }), null)<br/>    memory = optional(string, null)<br/>    name   = string<br/>    readiness_probe = optional(object({<br/>      failure_threshold = optional(number, null)<br/>      http_get = optional(object({<br/>        path      = optional(string, null)<br/>        port      = number<br/>        transport = optional(string, "HTTP")<br/>      }), null)<br/>      interval_seconds  = optional(number, null)<br/>      success_threshold = optional(number, null)<br/>      timeout_seconds   = optional(number, null)<br/>    }), null)<br/>    secrets = optional(list(object({<br/>      name                = string<br/>      key_vault_secret_id = optional(string, null)<br/>      identity            = optional(string, null)<br/>      value               = optional(string, null)<br/>    })), [])<br/>    startup_probe = optional(object({<br/>      failure_threshold = optional(number, null)<br/>      http_get = optional(object({<br/>        path      = optional(string, null)<br/>        port      = number<br/>        transport = optional(string, "HTTP")<br/>      }), null)<br/>      interval_seconds  = optional(number, null)<br/>      success_threshold = optional(number, null)<br/>      timeout_seconds   = optional(number, null)<br/>    }), null)<br/>  })</pre> | n/a | yes |
| <a name="input_container_registry_id"></a> [container\_registry\_id](#input\_container\_registry\_id) | id of the container registry to use for container app images | `string` | n/a | yes |
| <a name="input_custom_domain"></a> [custom\_domain](#input\_custom\_domain) | Custom domain to use for the container app | `string` | `""` | no |
| <a name="input_identity_type"></a> [identity\_type](#input\_identity\_type) | Managed identity type to be used by the container app | `string` | `"UserAssigned"` | no |
| <a name="input_ingress"></a> [ingress](#input\_ingress) | values to use for the ingress | <pre>object({<br/>    allow_insecure_connections = optional(bool, false)<br/>    custom_domain = optional(map(object({<br/>      certificate_binding_type = optional(string, "Disabled")<br/>      certificate_id           = string<br/>      name                     = string<br/>    })), {})<br/>    client_certificate_mode = optional(string, "ignore")<br/>    external_enabled        = optional(bool, false)<br/>    fqdn                    = optional(string)<br/>    target_port             = optional(number)<br/>    transport               = optional(string, "auto")<br/>  })</pre> | `{}` | no |
| <a name="input_key_vault_certificates"></a> [key\_vault\_certificates](#input\_key\_vault\_certificates) | Name of the key vault secret to use for the container app | `map(string)` | `{}` | no |
| <a name="input_key_vault_id"></a> [key\_vault\_id](#input\_key\_vault\_id) | Id of the key vault to use for the container app | `string` | `""` | no |
| <a name="input_location"></a> [location](#input\_location) | Location of the container app to create | `string` | n/a | yes |
| <a name="input_log_analytics_workspace_id"></a> [log\_analytics\_workspace\_id](#input\_log\_analytics\_workspace\_id) | Id of the log analytics workspace to use for container app logs | `string` | `""` | no |
| <a name="input_name"></a> [name](#input\_name) | Name of the container app to create | `string` | n/a | yes |
| <a name="input_private_network_subnet_id"></a> [private\_network\_subnet\_id](#input\_private\_network\_subnet\_id) | Id of the private subnet to use for the container app VNET Integration | `string` | `""` | no |
| <a name="input_replicas"></a> [replicas](#input\_replicas) | Number of replicas to create for the container app | <pre>object({<br/>    max = optional(number, null)<br/>    min = optional(number, null)<br/>  })</pre> | `{}` | no |
| <a name="input_resource_group"></a> [resource\_group](#input\_resource\_group) | Name of the resource group in which to create the container app | `string` | n/a | yes |
| <a name="input_storage"></a> [storage](#input\_storage) | Storage account to use for the container app environment | <pre>map(object({<br/>    access_key  = string<br/>    access_mode = optional(string, "ReadWrite")<br/>    mount_path  = optional(string, null)<br/>    name        = string<br/>    share_name  = string<br/>  }))</pre> | `{}` | no |
| <a name="input_tags"></a> [tags](#input\_tags) | Tags to apply to the container app | `map(string)` | `{}` | no |
| <a name="input_workload_profile"></a> [workload\_profile](#input\_workload\_profile) | Workload profile to use for the container app | `map(string)` | `{}` | no |

## Outputs

| Name | Description |
|------|-------------|
| <a name="output_default_domain"></a> [default\_domain](#output\_default\_domain) | Container app default domain |
| <a name="output_endpoint"></a> [endpoint](#output\_endpoint) | Container app endpoint |
| <a name="output_id"></a> [id](#output\_id) | Container app id |
| <a name="output_ip_address"></a> [ip\_address](#output\_ip\_address) | Container app Ingress load balancer IP address |
| <a name="output_latest_revision"></a> [latest\_revision](#output\_latest\_revision) | Container app latest revision |
| <a name="output_location"></a> [location](#output\_location) | Container app environment location |
| <a name="output_name"></a> [name](#output\_name) | Container app name |
| <a name="output_outbound_ips"></a> [outbound\_ips](#output\_outbound\_ips) | Container app outbound ips |
| <a name="output_resource_group"></a> [resource\_group](#output\_resource\_group) | Container app resource group name |
<!-- END OF PRE-COMMIT-TERRAFORM DOCS HOOK -->
