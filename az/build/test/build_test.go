package test

import (
	"flag"
	"os"
	"runtime"
	// "strings"
	"testing"

	"gopkg.in/yaml.v3"

	"github.com/gruntwork-io/terratest/modules/terraform"
	"github.com/stretchr/testify/assert"
	// "github.com/thedevsaddam/gojsonq/v2"
)

// Flag to destroy the target environment after tests
var destroy = flag.Bool("destroy", false, "destroy environment after tests")

func TestAzProject(t *testing.T) {
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

	// Check for az.yaml file
	if !assert.FileExists(t, terraformOptions.TerraformDir+"/../az.yaml") {
		t.Fail()
	}

	// Read and store the az.yaml or a local override
	yfile, err = os.ReadFile(terraformOptions.TerraformDir + "/../az.yaml")
	if err != nil {
		t.Fail()
	}

	az := make(map[string]interface{})
	err = yaml.Unmarshal(yfile, &az)
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

	// Verify configured Azurerm provider version
	if assert.Contains(t, version, "provider registry.terraform.io/hashicorp/azurerm v"+versions["azurerm_provider_version"].(string)) {
		t.Logf("Provider version check PASSED, expected hashicorp/azurerm version '~> %s', got \n%s", versions["azurerm_provider_version"].(string), version)
	} else {
		t.Errorf("Provider version check FAILED, expected hashicorp/azurrm version '~> %s', got \n%s", versions["azurerm_provider_version"].(string), version)
	}

	// Defer Terraform destroy only if flag is set
	if *destroy {
		defer terraform.Destroy(t, terraformOptions)
	}

	// Create resources
	terraform.Apply(t, terraformOptions)

	// Store outputs
	outputs := terraform.OutputAll(t, terraformOptions)

	// Test resource group name format
	expectedRgName := outputs["resource_group_name"].(string)
	if override, exists := inputs["resource_group_name_override"]; exists && override != nil {
		expectedRgName = override.(string)
	} else {
		expectedRgName = az["prefix"].(string) + "-" + env["environment"].(string) + "-" + env["location_short"].(string) + "-" + inputs["resource_group_name"].(string)
	}
	if assert.Equal(t, expectedRgName, outputs["resource_group_name"].(string)) {
		t.Logf("Resource group name test PASSED. Expected resource group name %s, got %s.", az["prefix"].(string)+"-"+env["environment"].(string)+"-"+env["location_short"].(string)+"-"+inputs["resource_group_name"].(string), outputs["resource_group_name"].(string))
	} else {
		t.Errorf("Resource group name test FAILED. Expected resource group name %s, got %s.", az["prefix"].(string)+"-"+env["environment"].(string)+"-"+env["location_short"].(string)+"-"+inputs["resource_group_name"].(string), outputs["resource_group_name"].(string))
	}

	// Test resource group location
	if assert.Equal(t, env["location"].(string), outputs["resource_group_location"].(string)) {
		t.Logf("Resource group location test PASSED. Expected resource group location %s, got %s.", env["location"].(string), outputs["resource_group_location"].(string))
	} else {
		t.Errorf("Resource group location test FAILED. Expected resource group location %s, got %s.", env["location"].(string), outputs["resource_group_location"].(string))
	}

	// Test container registry
	expectedContainerRegistryName := az["prefix"].(string) + env["environment"].(string) + env["location_short"].(string) + inputs["container_registry_name"].(string)
	if override, exists := inputs["container_registry_name_override"]; exists && override != nil {
		expectedContainerRegistryName = override.(string)
	}
	if assert.Equal(t, expectedContainerRegistryName, outputs["container_registry_name"].(string)) {
		t.Logf("Container registry name test PASSED. Expected container registry name %s, got %s.", expectedContainerRegistryName, outputs["container_registry_name"].(string))
	} else {
		t.Errorf("Container registry name test FAILED. Expected container registry name %s, got %s.", expectedContainerRegistryName, outputs["container_registry_name"].(string))
	}

	// Test storage account name format
	expectedSaName := outputs["storage_account_name"].(string)
	if override, exists := inputs["storage_account_name_override"]; exists && override != nil {
		expectedSaName = override.(string)
	} else {
		expectedSaName = az["prefix"].(string) + env["environment"].(string) + env["location_short"].(string) + inputs["storage_account_name"].(string)
	}
	if assert.Equal(t, expectedSaName, outputs["storage_account_name"].(string)) {
		t.Logf("Storage account name test PASSED. Expected storage account name %s, got %s.", az["prefix"].(string)+env["environment"].(string)+env["location_short"].(string)+inputs["storage_account_name"].(string), outputs["storage_account_name"].(string))
	} else {
		t.Errorf("Storage account name test FAILED. Expected storage account name %s, got %s.", az["prefix"].(string)+env["environment"].(string)+env["location_short"].(string)+inputs["storage_account_name"].(string), outputs["storage_account_name"].(string))
	}

	// Test storage account location
	if assert.Equal(t, env["location"].(string), outputs["storage_account_location"].(string)) {
		t.Logf("Storage account location test PASSED. Expected storage account location %s, got %s.", env["location"].(string), outputs["storage_account_location"].(string))
	} else {
		t.Errorf("Storage account location test FAILED. Expected storage account location %s, got %s.", env["location"].(string), outputs["storage_account_location"].(string))
	}

	// Get the state in json format
	// moduleJson := gojsonq.New().JSONString(terraform.Show(t, terraformOptions)).From("values.root_module.child_modules").
	// 	Where("address", "eq", "module.project_factory").
	// 	Select("child_modules")

	// childModules := moduleJson.Get()

	// Commented to use as reference when added other resources

	// // Traverse the state to locate project values
	// for _, childModule := range childModules.([]interface{})[0].(map[string]interface{})["child_modules"].([]interface{}) {
	// 	if childModule.(map[string]interface{})["address"].(string) == "module.project_factory.module.project-factory" {
	// 		for _, resource := range childModule.(map[string]interface{})["resources"].([]interface{}) {
	// 			if resource.(map[string]interface{})["address"].(string) == "module.project_factory.module.project-factory.google_project.main" {
	// 				// Test auto-create network
	// 				if assert.Equal(t, inputs["project"].(map[string]interface{})["auto_create_network"].(bool),
	// 					resource.(map[string]interface{})["values"].(map[string]interface{})["auto_create_network"].(bool)) {
	// 					t.Logf("Default network creation test PASSED. Expected auto_create_network to be set %t, got %t.",
	// 						inputs["project"].(map[string]interface{})["auto_create_network"].(bool),
	// 						resource.(map[string]interface{})["values"].(map[string]interface{})["auto_create_network"].(bool))
	// 				} else {
	// 					t.Logf("Default network creation test FAILED. Expected auto_create_network to be set %t, got %t.",
	// 						inputs["project"].(map[string]interface{})["auto_create_network"].(bool),
	// 						resource.(map[string]interface{})["values"].(map[string]interface{})["auto_create_network"].(bool))
	// 				}
	// 				// Test parent folder id
	// 				if assert.Equal(t, strings.Split(outputs["folder_id"].(string), "/")[1],
	// 					resource.(map[string]interface{})["values"].(map[string]interface{})["folder_id"].(string)) {
	// 					t.Logf("Project parent folder ID test PASSED. Expected project parent folder ID %s, got %s.",
	// 						strings.Split(outputs["folder_id"].(string), "/")[1],
	// 						resource.(map[string]interface{})["values"].(map[string]interface{})["folder_id"].(string))
	// 				} else {
	// 					t.Logf("Project parent folder ID test FAILED. Expected project parent folder ID %s, got %s.",
	// 						strings.Split(outputs["folder_id"].(string), "/")[1],
	// 						resource.(map[string]interface{})["values"].(map[string]interface{})["folder_id"].(string))
	// 				}
	// 			}
	// 		}
	// 	}
	// }

}
