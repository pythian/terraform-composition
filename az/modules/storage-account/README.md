# storage-account

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
| [azurerm_storage_account.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/storage_account) | resource |
| [azurerm_storage_container.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/storage_container) | resource |
| [azurerm_storage_data_lake_gen2_filesystem.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/storage_data_lake_gen2_filesystem) | resource |
| [azurerm_storage_share.main](https://registry.terraform.io/providers/hashicorp/azurerm/latest/docs/resources/storage_share) | resource |

## Inputs

| Name | Description | Type | Default | Required |
|------|-------------|------|---------|:--------:|
| <a name="input_containers"></a> [containers](#input\_containers) | List of containers to create in the storage account | `set(string)` | `[]` | no |
| <a name="input_hierarchical_namespace_enabled"></a> [hierarchical\_namespace\_enabled](#input\_hierarchical\_namespace\_enabled) | Whether the storage account has hierarchical namespaces enabled | `bool` | `false` | no |
| <a name="input_kind"></a> [kind](#input\_kind) | Type of storage account to create (BlobStorage, BlockBlobStorage, FileStorage, Storage, StorageV2) | `string` | `"StorageV2"` | no |
| <a name="input_location"></a> [location](#input\_location) | Location of the storage account to create | `string` | n/a | yes |
| <a name="input_name"></a> [name](#input\_name) | Name of the storage account to create | `string` | n/a | yes |
| <a name="input_network_rules"></a> [network\_rules](#input\_network\_rules) | Network rules for the storage account | <pre>map(object({<br/>    ip_rules                   = optional(list(string), [])<br/>    virtual_network_subnet_ids = optional(list(string), [])<br/>    bypass                     = optional(list(string), [])<br/>    default_action             = optional(string, "Deny")<br/>  }))</pre> | `{}` | no |
| <a name="input_public_network_access_enabled"></a> [public\_network\_access\_enabled](#input\_public\_network\_access\_enabled) | Whether or not public network access is allowed for the storage account. When false, only private endpoints can access the storage account. | `bool` | `true` | no |
| <a name="input_replication_type"></a> [replication\_type](#input\_replication\_type) | Type of replication to use (LRS, GRS, RAGRS, ZRS, GZRS, RAGZRS) | `string` | `"LRS"` | no |
| <a name="input_resource_group"></a> [resource\_group](#input\_resource\_group) | Name of the resource group in which to create the storage account | `string` | n/a | yes |
| <a name="input_shares"></a> [shares](#input\_shares) | List of shares to create in the storage account | <pre>map(object({<br/>    name             = string<br/>    enabled_protocol = string<br/>    quota            = number<br/>    acl = map(object({<br/>      id          = string<br/>      permissions = string<br/>    }))<br/>  }))</pre> | `{}` | no |
| <a name="input_tags"></a> [tags](#input\_tags) | Tags to apply to the storage account | `map(string)` | `{}` | no |
| <a name="input_tier"></a> [tier](#input\_tier) | Account tier for the resource (Standard, Premium) | `string` | `"Standard"` | no |

## Outputs

| Name | Description |
|------|-------------|
| <a name="output_access_key"></a> [access\_key](#output\_access\_key) | Storage account access key |
| <a name="output_container_ids"></a> [container\_ids](#output\_container\_ids) | Containers created under the storage account |
| <a name="output_container_names"></a> [container\_names](#output\_container\_names) | Containers created under the storage account |
| <a name="output_hierarchical_namespaces_enabled"></a> [hierarchical\_namespaces\_enabled](#output\_hierarchical\_namespaces\_enabled) | Whether hierarchical namespaces are enabled |
| <a name="output_id"></a> [id](#output\_id) | Storage account ID |
| <a name="output_kind"></a> [kind](#output\_kind) | Storage account kind |
| <a name="output_location"></a> [location](#output\_location) | Storage account location |
| <a name="output_name"></a> [name](#output\_name) | Storage account name |
| <a name="output_primary_blob_endpoint"></a> [primary\_blob\_endpoint](#output\_primary\_blob\_endpoint) | Storage account primary blob endpoint |
| <a name="output_primary_queue_endpoint"></a> [primary\_queue\_endpoint](#output\_primary\_queue\_endpoint) | Storage account primary queue endpoint |
| <a name="output_replication_type"></a> [replication\_type](#output\_replication\_type) | Storage account replication type |
| <a name="output_resource_group"></a> [resource\_group](#output\_resource\_group) | Storage account parent resource group |
| <a name="output_share_ids"></a> [share\_ids](#output\_share\_ids) | Shares created under the storage account |
| <a name="output_share_names"></a> [share\_names](#output\_share\_names) | Shares created under the storage account |
| <a name="output_storage_data_lake_filesystem_id"></a> [storage\_data\_lake\_filesystem\_id](#output\_storage\_data\_lake\_filesystem\_id) | Data lake filesystem created under the storage account |
| <a name="output_tier"></a> [tier](#output\_tier) | Storage account tier |
<!-- END OF PRE-COMMIT-TERRAFORM DOCS HOOK -->
