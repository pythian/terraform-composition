# app-service

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
| [azurerm_app_service_certificate.kv](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/app_service_certificate) | resource |
| [azurerm_app_service_custom_hostname_binding.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/app_service_custom_hostname_binding) | resource |
| [azurerm_linux_web_app.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/linux_web_app) | resource |
| [azurerm_monitor_autoscale_setting.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/monitor_autoscale_setting) | resource |
| [azurerm_role_assignment.kv](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/role_assignment) | resource |
| [azurerm_role_assignment.push_pull](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/role_assignment) | resource |
| [azurerm_service_plan.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/service_plan) | resource |
| [azurerm_user_assigned_identity.app](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/user_assigned_identity) | resource |
| [azurerm_key_vault_certificate.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/data-sources/key_vault_certificate) | data source |
| [azurerm_key_vault_secret.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/data-sources/key_vault_secret) | data source |

## Inputs

| Name | Description | Type | Default | Required |
|------|-------------|------|---------|:--------:|
| <a name="input_acr_id"></a> [acr\_id](#input\_acr\_id) | Azure Container Registry ID | `string` | `""` | no |
| <a name="input_app_settings"></a> [app\_settings](#input\_app\_settings) | App settings for each web app | `map(map(string))` | `{}` | no |
| <a name="input_auto_scale_profile"></a> [auto\_scale\_profile](#input\_auto\_scale\_profile) | Auto scale profile for the App Service Plan | <pre>map(object({<br/>    capacity = object({<br/>      default = number<br/>      minimum = number<br/>      maximum = number<br/>    })<br/>    rules = list(object({<br/>      metric_trigger = object({<br/>        metric_name      = string<br/>        metric_namespace = string<br/>        time_grain       = string<br/>        statistic        = string<br/>        time_window      = string<br/>        time_aggregation = string<br/>        operator         = string<br/>        threshold        = number<br/>      })<br/>      scale_action = object({<br/>        direction = string<br/>        type      = string<br/>        value     = string<br/>        cooldown  = string<br/>      })<br/>    }))<br/>  }))</pre> | `{}` | no |
| <a name="input_client_certificate_mode"></a> [client\_certificate\_mode](#input\_client\_certificate\_mode) | Client certificate mode for web app | `map(string)` | n/a | yes |
| <a name="input_hostnames"></a> [hostnames](#input\_hostnames) | Hostnames associated with the App Service | <pre>map(object({<br/>    webapp_name = string<br/>  }))</pre> | `{}` | no |
| <a name="input_ip_restriction"></a> [ip\_restriction](#input\_ip\_restriction) | IP restriction for applications | `map(map(any))` | `{}` | no |
| <a name="input_key_vault_certificate"></a> [key\_vault\_certificate](#input\_key\_vault\_certificate) | Key Vault certificate name for the web app | `map(string)` | `{}` | no |
| <a name="input_key_vault_id"></a> [key\_vault\_id](#input\_key\_vault\_id) | Key Vault ID for the web app | `string` | n/a | yes |
| <a name="input_location"></a> [location](#input\_location) | Location of the App Service Plan to create | `string` | n/a | yes |
| <a name="input_mysql_password_secret_name"></a> [mysql\_password\_secret\_name](#input\_mysql\_password\_secret\_name) | MySQL password secret name in Key Vault | `string` | `""` | no |
| <a name="input_mysql_server_address"></a> [mysql\_server\_address](#input\_mysql\_server\_address) | MySQL server address | `string` | `""` | no |
| <a name="input_name"></a> [name](#input\_name) | Name of the App Service Plan to create | `string` | n/a | yes |
| <a name="input_prefix"></a> [prefix](#input\_prefix) | Prefix to be added to the domain | `string` | `""` | no |
| <a name="input_resource_group"></a> [resource\_group](#input\_resource\_group) | Name of the resource group in which to create the App Service Plan | `string` | n/a | yes |
| <a name="input_site_config"></a> [site\_config](#input\_site\_config) | Site configurations for each web app | `map(map(any))` | `{}` | no |
| <a name="input_sku"></a> [sku](#input\_sku) | SKU name of the App Service Plan to create | `string` | `"P0v3"` | no |
| <a name="input_storage_account"></a> [storage\_account](#input\_storage\_account) | Storage account to be used for the web app | <pre>map(object({<br/>    access_key   = optional(string, null)<br/>    account_name = optional(string, null)<br/>    name         = optional(string, null)<br/>    share_name   = optional(string, null)<br/>    type         = optional(string, null)<br/>    mount_path   = optional(string, null)<br/>  }))</pre> | `{}` | no |
| <a name="input_tags"></a> [tags](#input\_tags) | Tags to be applied to the resources | `map(string)` | `{}` | no |
| <a name="input_virtual_network_subnet_id"></a> [virtual\_network\_subnet\_id](#input\_virtual\_network\_subnet\_id) | Virtual Network to attach the website into | `map(string)` | `{}` | no |
| <a name="input_webapps"></a> [webapps](#input\_webapps) | Web Applications to be deployed in this App Service Plan | `set(string)` | n/a | yes |

## Outputs

| Name | Description |
|------|-------------|
| <a name="output_id"></a> [id](#output\_id) | App Service Plan ID |
| <a name="output_location"></a> [location](#output\_location) | App Service Plan location |
| <a name="output_name"></a> [name](#output\_name) | App Service Plan name |
| <a name="output_resource_group"></a> [resource\_group](#output\_resource\_group) | App Service Plan parent resource group |
| <a name="output_webapp_hostnames"></a> [webapp\_hostnames](#output\_webapp\_hostnames) | Web Applications deployed fqdns |
| <a name="output_webapp_ids"></a> [webapp\_ids](#output\_webapp\_ids) | Web Applications deployed ids |
| <a name="output_webapp_ids_map"></a> [webapp\_ids\_map](#output\_webapp\_ids\_map) | Web Applications deployed ids in map format |
| <a name="output_webapp_names"></a> [webapp\_names](#output\_webapp\_names) | Web Applications deployed names |
<!-- END OF PRE-COMMIT-TERRAFORM DOCS HOOK -->
