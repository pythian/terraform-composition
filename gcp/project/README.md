<!-- BEGIN_TF_DOCS -->
## Requirements

No requirements.

## Providers

No providers.

## Modules

| Name | Source | Version |
|------|--------|---------|
| <a name="module_folder"></a> [folder](#module\_folder) | terraform-google-modules/folders/google | 4.0.0 |
| <a name="module_iam_audit_config"></a> [iam\_audit\_config](#module\_iam\_audit\_config) | terraform-google-modules/iam/google//modules/audit_config | 7.7.0 |
| <a name="module_project_factory"></a> [project\_factory](#module\_project\_factory) | terraform-google-modules/project-factory/google | 14.3.0 |
| <a name="module_project_name"></a> [project\_name](#module\_project\_name) | git@github.com:kpeder/terraform-module.git | 0.1.0 |

## Resources

No resources.

## Inputs

No inputs.

## Outputs

| Name | Description |
|------|-------------|
| <a name="output_project_id"></a> [project\_id](#output\_project\_id) | ID of the configured project |
<!-- END_TF_DOCS -->
<!-- BEGINNING OF PRE-COMMIT-TERRAFORM DOCS HOOK -->
## Requirements

No requirements.

## Providers

| Name | Version |
|------|---------|
| <a name="provider_google"></a> [google](#provider\_google) | 4.84.0 |

## Modules

| Name | Source | Version |
|------|--------|---------|
| <a name="module_folder"></a> [folder](#module\_folder) | terraform-google-modules/folders/google | 4.0.0 |
| <a name="module_iam_audit_config"></a> [iam\_audit\_config](#module\_iam\_audit\_config) | terraform-google-modules/iam/google//modules/audit_config | 7.7.0 |
| <a name="module_project_factory"></a> [project\_factory](#module\_project\_factory) | terraform-google-modules/project-factory/google | 14.3.0 |
| <a name="module_project_name"></a> [project\_name](#module\_project\_name) | git@github.com:kpeder/terraform-module.git | 0.1.0 |

## Resources

| Name | Type |
|------|------|
| [google_project.main](https://registry.terraform.io/providers/hashicorp/google/latest/docs/data-sources/project) | data source |

## Inputs

No inputs.

## Outputs

| Name | Description |
|------|-------------|
| <a name="output_billing_account"></a> [billing\_account](#output\_billing\_account) | Billing account of the configured project |
| <a name="output_enabled_apis"></a> [enabled\_apis](#output\_enabled\_apis) | APIs enabled on the configured project |
| <a name="output_folder_id"></a> [folder\_id](#output\_folder\_id) | ID of the configured project's parent folder |
| <a name="output_folder_name"></a> [folder\_name](#output\_folder\_name) | Name of the configured project's parent folder |
| <a name="output_project_id"></a> [project\_id](#output\_project\_id) | ID of the configured project |
| <a name="output_project_labels"></a> [project\_labels](#output\_project\_labels) | Labels configured on the project |
| <a name="output_project_name"></a> [project\_name](#output\_project\_name) | Name of the configured project |
<!-- END OF PRE-COMMIT-TERRAFORM DOCS HOOK -->
