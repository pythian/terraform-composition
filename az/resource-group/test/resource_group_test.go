package test

import (
	"flag"
	"os"
	"runtime"
	"strings"
	"testing"

	"gopkg.in/yaml.v3"

	"github.com/gruntwork-io/terratest/modules/terraform"
	"github.com/stretchr/testify/assert"
	"github.com/thedevsaddam/gojsonq/v2"
)

// Flag to destroy the target environment after tests
var destroy = flag.Bool("destroy", false, "destroy environment after tests")

func TestGCPProject(t *testing.T) {
	// Set execution directory
	terraformOptions := &terraform.Options{
		TerraformDir: "../.",
	}

	// Check for versions file
	if !assert.FileExists(t, terraformOptions.TerraformDir+"/../versions.yaml") {
		t.Fail()
	}

	// Read and store the versions.yaml
	yfile, err := os.ReadFile(terraformOptions.TerraformDir + "/../versions.yaml")
	if err != nil {
		t.Fail()
	}

	versions := make(map[string]interface{})
	err = yaml.Unmarshal(yfile, &versions)
	if err != nil {
		t.Fail()
	}

	// Read the version output and verify the configured version
	goversion := runtime.Version()

	if assert.GreaterOrEqual(t, goversion, "go"+versions["golang_runtime_version"].(string)) {
		t.Logf("Go runtime version check PASSED, expected version >= '%s', got '%s'", "go"+versions["golang_runtime_version"].(string), goversion)
	} else {
		t.Errorf("Go runtime version check FAILED, expected version >= '%s', got '%s'", "go"+versions["golang_runtime_version"].(string), goversion)
	}

	// Check for env.yaml file
	if !assert.FileExists(t, terraformOptions.TerraformDir+"/../env.yaml") {
		t.Fail()
	}

	// Read and store the env.yaml
	yfile, err = os.ReadFile(terraformOptions.TerraformDir + "/../env.yaml")
	if err != nil {
		t.Fail()
	}

	env := make(map[string]interface{})
	err = yaml.Unmarshal(yfile, &env)
	if err != nil {
		t.Fail()
	}

	// Check for gcp.yaml file or a local override
	if !assert.FileExists(t, terraformOptions.TerraformDir+"/../local.gcp.yaml") {
		if !assert.FileExists(t, terraformOptions.TerraformDir+"/../gcp.yaml") {
			t.Fail()
		}
	}

	// Read and store the gcp.yaml or a local override
	if assert.FileExists(t, terraformOptions.TerraformDir+"/../local.gcp.yaml") {
		yfile, err = os.ReadFile(terraformOptions.TerraformDir + "/../local.gcp.yaml")
		if err != nil {
			t.Fail()
		}
	} else {
		yfile, err = os.ReadFile(terraformOptions.TerraformDir + "/../gcp.yaml")
		if err != nil {
			t.Fail()
		}
	}

	gcp := make(map[string]interface{})
	err = yaml.Unmarshal(yfile, &gcp)
	if err != nil {
		t.Fail()
	}

	// Check for inputs.yaml file
	if !assert.FileExists(t, terraformOptions.TerraformDir+"/inputs.yaml") {
		t.Fail()
	}

	// Read and store the inputs.yaml
	yfile, err = os.ReadFile(terraformOptions.TerraformDir + "/inputs.yaml")
	if err != nil {
		t.Fail()
	}

	inputs := make(map[string]interface{})
	err = yaml.Unmarshal(yfile, &inputs)
	if err != nil {
		t.Fail()
	}

	// Sanity test
	terraform.Validate(t, terraformOptions)

	// Initialize the deployment
	terraform.Init(t, terraformOptions)

	// Read the version command output
	version := terraform.RunTerraformCommand(t, terraformOptions, terraform.FormatArgs(terraformOptions, "version")...)

	// Verify configured Terraform version
	if assert.Contains(t, version, "Terraform v"+versions["terraform_binary_version"].(string)) {
		t.Logf("Terraform version check PASSED, expected version '~> %s', got \n%s", versions["terraform_binary_version"].(string), version)
	} else {
		t.Errorf("Terraform version check FAILED, expected version '~> %s', got \n%s", versions["terraform_binary_version"].(string), version)
	}

	// Verify configured Google provider version
	if assert.Contains(t, version, "provider registry.terraform.io/hashicorp/google v"+versions["google_provider_version"].(string)) {
		t.Logf("Provider version check PASSED, expected hashicorp/google version '~> %s', got \n%s", versions["google_provider_version"].(string), version)
	} else {
		t.Errorf("Provider version check FAILED, expected hashicorp/google version '~> %s', got \n%s", versions["google_provider_version"].(string), version)
	}

	// Defer Terraform destroy only if flag is set
	if *destroy {
		defer terraform.Destroy(t, terraformOptions)
	}

	// Create resources
	terraform.Apply(t, terraformOptions)

	// Store outputs
	outputs := terraform.OutputAll(t, terraformOptions)

	// Test billing account
	if assert.Equal(t, gcp["billing_account"].(string), outputs["billing_account"].(string)) {
		t.Logf("Billing account test PASSED. Expected billing account id %s, got %s.", gcp["billing_account"].(string), outputs["billing_account"].(string))
	} else {
		t.Errorf("Billing account test FAILED. Expected billing account id %s, got %s.", gcp["billing_account"].(string), outputs["billing_account"].(string))
	}

	// Test parent folder name
	if assert.Equal(t, gcp["prefix"].(string), strings.Split(outputs["folder_name"].(string), "-")[0]) {
		t.Logf("Folder prefix test PASSED. Expected folder name to start with %s, got %s.", gcp["prefix"], strings.Split(outputs["folder_name"].(string), "-")[0])
	} else {
		t.Errorf("Folder prefix test FAILED. Expected folder name to start with %s, got %s.", gcp["prefix"], strings.Split(outputs["folder_name"].(string), "-")[0])
	}

	// Test project id
	if assert.Equal(t, gcp["prefix"].(string), strings.Split(outputs["project_id"].(string), "-")[0]) {
		t.Logf("Project prefix test PASSED. Expected project name to start with %s, got %s.", gcp["prefix"].(string), strings.Split(outputs["project_id"].(string), "-")[0])
	} else {
		t.Errorf("Project prefix test FAILED. Expected project name to start with %s, got %s.", gcp["prefix"].(string), strings.Split(outputs["project_id"].(string), "-")[0])
	}
	if inputs["project"].(map[string]interface{})["random_project_id"].(bool) {
		if (assert.Len(t, strings.Split(outputs["project_id"].(string), "-"), len(strings.Split(outputs["project_name"].(string), "-"))+1)) &&
			(assert.Contains(t, outputs["project_id"].(string), outputs["project_name"].(string))) {
			t.Logf("Project suffix test PASSED. Expected project_id to have a random suffix, got %s.",
				strings.Split(outputs["project_id"].(string), "-")[len(strings.Split(outputs["project_name"].(string), "-"))])
		} else {
			t.Error("Project suffix test FAILED. Expected project_id to have a random suffix, got nil.")
		}
	}

	// Test project labels
	labelFailed := false
	labels := []string{}
	for k, v := range env["labels"].(map[string]interface{}) {
		val, set := outputs["project_labels"].(map[string]interface{})[k].(string)
		if set {
			if !assert.Equal(t, v, val) {
				t.Errorf("Project labels test FAILED. Expected label %s to be set to %s, got %s.", k, v, val)
				labelFailed = true
			} else {
				labels = append(labels, k)
			}
		} else {
			t.Errorf("Project labels test FAILED. Expected label %s to be set, not found.", k)
			labelFailed = true
		}
	}
	if !labelFailed {
		t.Logf("Project labels test PASSED. Expected project labels are set: %v", labels)
	}

	// Test enabled APIs
	apiFailed := false
	apis := []string{}
	for _, api := range inputs["project"].(map[string]interface{})["activate_apis"].([]interface{}) {
		if !assert.Contains(t, outputs["enabled_apis"].([]interface{}), api) {
			t.Errorf("Project APIs test FAILED. Expected project API %s to be enabled, not found.", api)
			apiFailed = true
		} else {
			apis = append(apis, api.(string))
		}
	}
	if !apiFailed {
		t.Logf("Project APIs test PASSED. Expected project APIs are enabled: %v", apis)
	}

	// Test audit configuration
	for _, inputConfig := range inputs["audit_log_config"].([]interface{}) {
		configFailed := true
		for _, outputConfig := range outputs["audit_log_config"].([]interface{}) {
			if inputConfig.(map[string]interface{})["service"].(string) == outputConfig.(map[string]interface{})["service"].(string) &&
				inputConfig.(map[string]interface{})["log_type"].(string) == outputConfig.(map[string]interface{})["log_type"].(string) {
				t.Logf("Audit log config test PASSED. Expected log_type %s to be enabled for service %s, got log_type %s for service %s.",
					inputConfig.(map[string]interface{})["log_type"].(string), inputConfig.(map[string]interface{})["service"].(string),
					outputConfig.(map[string]interface{})["log_type"].(string), outputConfig.(map[string]interface{})["service"].(string))
				configFailed = false
			}
		}
		if configFailed == true {
			t.Errorf("Audit log config test FAILED. Expected log_type %s to be enabled for service %s, not found.",
				inputConfig.(map[string]interface{})["log_type"].(string), inputConfig.(map[string]interface{})["service"].(string))
		}
	}

	// Get the state in json format
	moduleJson := gojsonq.New().JSONString(terraform.Show(t, terraformOptions)).From("values.root_module.child_modules").
		Where("address", "eq", "module.project_factory").
		Select("child_modules")

	childModules := moduleJson.Get()

	// Traverse the state to locate project values
	for _, childModule := range childModules.([]interface{})[0].(map[string]interface{})["child_modules"].([]interface{}) {
		if childModule.(map[string]interface{})["address"].(string) == "module.project_factory.module.project-factory" {
			for _, resource := range childModule.(map[string]interface{})["resources"].([]interface{}) {
				if resource.(map[string]interface{})["address"].(string) == "module.project_factory.module.project-factory.google_project.main" {
					// Test auto-create network
					if assert.Equal(t, inputs["project"].(map[string]interface{})["auto_create_network"].(bool),
						resource.(map[string]interface{})["values"].(map[string]interface{})["auto_create_network"].(bool)) {
						t.Logf("Default network creation test PASSED. Expected auto_create_network to be set %t, got %t.",
							inputs["project"].(map[string]interface{})["auto_create_network"].(bool),
							resource.(map[string]interface{})["values"].(map[string]interface{})["auto_create_network"].(bool))
					} else {
						t.Logf("Default network creation test FAILED. Expected auto_create_network to be set %t, got %t.",
							inputs["project"].(map[string]interface{})["auto_create_network"].(bool),
							resource.(map[string]interface{})["values"].(map[string]interface{})["auto_create_network"].(bool))
					}
					// Test parent folder id
					if assert.Equal(t, strings.Split(outputs["folder_id"].(string), "/")[1],
						resource.(map[string]interface{})["values"].(map[string]interface{})["folder_id"].(string)) {
						t.Logf("Project parent folder ID test PASSED. Expected project parent folder ID %s, got %s.",
							strings.Split(outputs["folder_id"].(string), "/")[1],
							resource.(map[string]interface{})["values"].(map[string]interface{})["folder_id"].(string))
					} else {
						t.Logf("Project parent folder ID test FAILED. Expected project parent folder ID %s, got %s.",
							strings.Split(outputs["folder_id"].(string), "/")[1],
							resource.(map[string]interface{})["values"].(map[string]interface{})["folder_id"].(string))
					}
				}
			}
		}
	}

	// Test state bucket name
	if assert.Equal(t, outputs["project_name"].(string)+"-state", outputs["state_bucket_name"].(string)) {
		t.Logf("State bucket name test PASSED. Expected state bucket name %s, got %s.", outputs["project_name"].(string)+"-state", outputs["state_bucket_name"].(string))
	} else {
		t.Errorf("State bucket name test FAILED. Expected state bucket name %s, got %s.", outputs["project_name"].(string)+"-state", outputs["state_bucket_name"].(string))
	}

	// Test state bucket labels
	labelFailed = false
	labels = []string{}
	for k, v := range env["labels"].(map[string]interface{}) {
		val, set := outputs["state_bucket_labels"].(map[string]interface{})[k].(string)
		if set {
			if !assert.Equal(t, v, val) {
				t.Errorf("State bucket labels test FAILED. Expected label %s to be set to %s, got %s.", k, v, val)
				labelFailed = true
			} else {
				labels = append(labels, k)
			}
		} else {
			t.Errorf("State bucket labels test FAILED. Expected label %s to be set, not found.", k)
			labelFailed = true
		}
	}
	if !labelFailed {
		t.Logf("State bucket labels test PASSED. Expected state bucket labels are set: %v", labels)
	}

	// Test state bucket project
	if assert.Equal(t, gcp["build_project"].(string), outputs["state_bucket_project"].(string)) {
		t.Logf("State bucket project test PASSED. Expected state bucket project %s, got %s.", gcp["build_project"].(string), outputs["state_bucket_project"].(string))
	} else {
		t.Errorf("State bucket project test FAILED. Expected state bucket project %s, got %s.", gcp["build_project"].(string), outputs["state_bucket_project"].(string))
	}

	// Test state bucket versioning, should be explicitly true
	if assert.True(t, outputs["state_bucket_versioning"].([]interface{})[0].(map[string]interface{})["enabled"].(bool)) {
		t.Logf("State bucket versioning test PASSED. Expected state bucket versioning to be set %t, got %t.",
			true, outputs["state_bucket_versioning"].([]interface{})[0].(map[string]interface{})["enabled"].(bool))
	} else {
		t.Errorf("State bucket versioning test PASSED. Expected state bucket versioning to be set %t, got %t.",
			true, outputs["state_bucket_versioning"].([]interface{})[0].(map[string]interface{})["enabled"].(bool))
	}
}
