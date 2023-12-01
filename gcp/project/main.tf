locals {
  env      = yamldecode(file("../env.yaml"))
  inputs   = yamldecode(file("./inputs.yaml"))
  gcp      = fileexists("../local.gcp.yaml") ? yamldecode(file("../local.gcp.yaml")) : yamldecode(file("../gcp.yaml"))
  versions = yamldecode(file("../versions.yaml"))
}

module "folder" {
  source  = "terraform-google-modules/folders/google"
  version = "4.0.0"

  names  = local.inputs.folder.names
  parent = format("%s/%s", local.gcp.parent_type, local.gcp.parent_id)
  prefix = try(local.gcp.prefix, null)
}

module "iam_audit_config" {
  source  = "terraform-google-modules/iam/google//modules/audit_config"
  version = "7.7.0"

  audit_log_config = local.inputs.audit_log_config
  project          = module.project_factory.project_id
}

module "project_factory" {
  source  = "terraform-google-modules/project-factory/google"
  version = "14.3.0"

  activate_apis           = local.inputs.project.activate_apis
  auto_create_network     = local.inputs.project.auto_create_network
  billing_account         = coalesce(local.inputs.project.billing_account_override, local.gcp.billing_account)
  default_service_account = local.inputs.project.default_service_account
  folder_id               = local.gcp.parent_type == "folders" ? coalesce(local.inputs.project.folder_id_override, local.gcp.parent_id, "") : ""
  labels                  = local.env.labels
  name                    = coalesce(local.inputs.project.project_id_override, module.project_name.random_pet)
  org_id                  = local.gcp.organization_id
  random_project_id       = local.inputs.project.random_project_id
}

module "project_name" {
  source = "git@github.com:kpeder/terraform-module.git?ref=0.1.0"

  length = 1
  prefix = try(local.gcp.prefix, null)
}

output "enabled_apis" {
  description = "APIs enabled on the configured project"
  value       = module.project_factory.enabled_apis
}

output "project_id" {
  description = "ID of the configured project"
  value       = module.project_factory.project_id
}

output "project_name" {
  description = "Name of the configured project"
  value       = module.project_factory.project_name
}
