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

	// Check for networks.yaml file
	if !assert.FileExists(t, terraformOptions.TerraformDir+"/../networks.yaml") {
		t.Fail()
	}

	// Read and store the inputs.yaml
	yfile, err = os.ReadFile(terraformOptions.TerraformDir + "/../networks.yaml")
	if err != nil {
		t.Fail()
	}

	networks := make(map[string]interface{})
	err = yaml.Unmarshal(yfile, &networks)
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

	//Test app gateway
	// expectedAppGatewayName := az["prefix"].(string) + "-" + env["environment"].(string) + "-" + env["location_short"].(string) + "-" + inputs["application_gateway_name"].(string)
	// if override, exists := inputs["application_gateway_name_override"]; exists && override != nil {
	// 	expectedAppGatewayName = override.(string)
	// }

	// if assert.Equal(t, expectedAppGatewayName, outputs["application_gateway_name"].(string)) {
	// 	t.Logf("Application gateway name test PASSED. Expected application gateway name %s, got %s.", expectedAppGatewayName, outputs["application_gateway_name"].(string))
	// } else {
	// 	t.Errorf("Application gateway name test FAILED. Expected application gateway name %s, got %s.", expectedAppGatewayName, outputs["application_gateway_name"].(string))
	// }

	//Test app service plan and websites
	expectedAppServicePlanName := az["prefix"].(string) + "-" + env["environment"].(string) + "-" + env["location_short"].(string) + "-" + inputs["app_service_name"].(string)
	if override, exists := inputs["app_service_name_override"]; exists && override != nil {
		expectedAppServicePlanName = override.(string)
	}

	if assert.Equal(t, expectedAppServicePlanName, outputs["app_service_plan_name"].(string)) {
		t.Logf("App service plan name test PASSED. Expected app service plan name %s, got %s.", expectedAppServicePlanName, outputs["app_service_plan_name"].(string))
	} else {
		t.Errorf("App service plan name test FAILED. Expected app service plan name %s, got %s.", expectedAppServicePlanName, outputs["app_service_plan_name"].(string))
	}
	if webapps, ok := inputs["app_service_webapps"].([]interface{}); ok && webapps != nil {
		for i := 0; i < len(webapps); i++ {
			expectedWebAppName := webapps[i].(string)
			if assert.Contains(t, outputs["app_service_webapp_names"].([]interface{}), expectedWebAppName) {
				t.Logf("App service webapp name test PASSED. Expected app service webapp name %s, got %s.", expectedWebAppName, outputs["app_service_webapp_names"].([]interface{}))
			} else {
				t.Errorf("App service webapp name test FAILED. Expected app service webapp name %s, got %s.", expectedWebAppName, outputs["app_service_webapp_names"].([]interface{}))
			}
		}
	} else {
		t.Errorf("App service webapps input is not properly configured in inputs.yaml")
	}

	//Test key vault resource group
	expectedKeyVaultRgName := az["prefix"].(string) + "-" + env["environment"].(string) + "-" + env["location_short"].(string) + "-" + inputs["key_vault_resource_group_name"].(string)
	if override, exists := inputs["key_vault_resource_group_name_override"]; exists && override != nil {
		expectedKeyVaultRgName = override.(string)
	}

	if assert.Equal(t, expectedKeyVaultRgName, outputs["key_vault_resource_group_name"].(string)) {
		t.Logf("Key vault resource group name test PASSED. Expected key vault resource group name %s, got %s.", expectedKeyVaultRgName, outputs["key_vault_resource_group_name"].(string))
	} else {
		t.Errorf("Key vault resource group name test FAILED. Expected key vault resource group name %s, got %s.", expectedKeyVaultRgName, outputs["key_vault_resource_group_name"].(string))
	}

	//Test key vault
	expectedKeyVaultName := az["prefix"].(string) + "-" + env["environment"].(string) + "-" + env["location_short"].(string) + "-" + inputs["key_vault_name"].(string)
	if override, exists := inputs["key_vault_name_override"]; exists && override != nil {
		expectedKeyVaultName = override.(string)
	}
	if assert.Equal(t, expectedKeyVaultName, outputs["key_vault_name"].(string)) {
		t.Logf("Key vault name test PASSED. Expected key vault name %s, got %s.", expectedKeyVaultName, outputs["key_vault_name"].(string))
	} else {
		t.Errorf("Key vault name test FAILED. Expected key vault name %s, got %s.", expectedKeyVaultName, outputs["key_vault_name"].(string))
	}

	// test mysql
	expectedMysqlName := az["prefix"].(string) + "-" + env["environment"].(string) + "-" + env["location_short"].(string) + "-" + inputs["mysql_name"].(string)
	if override, exists := inputs["mysql_name_override"]; exists && override != nil {
		expectedMysqlName = override.(string)
	}
	if assert.Equal(t, expectedMysqlName, outputs["mysql_name"].(string)) {
		t.Logf("Mysql name test PASSED. Expected mysql name %s, got %s.", expectedMysqlName, outputs["mysql_name"].(string))
	} else {
		t.Errorf("Mysql name test FAILED. Expected mysql name %s, got %s.", expectedMysqlName, outputs["mysql_name"].(string))
	}

	// Test resource group name format
	expectedRgName := az["prefix"].(string) + "-" + env["environment"].(string) + "-" + env["location_short"].(string) + "-" + inputs["resource_group_name"].(string)
	if override, exists := inputs["resource_group_name_override"]; exists && override != nil {
		expectedRgName = override.(string)
	}

	if assert.Equal(t, expectedRgName, outputs["resource_group_name"].(string)) {
		t.Logf("Resource group name test PASSED. Expected resource group name %s, got %s.", expectedRgName, outputs["resource_group_name"].(string))
	} else {
		t.Errorf("Resource group name test FAILED. Expected resource group name %s, got %s.", expectedRgName, outputs["resource_group_name"].(string))
	}

	// Test resource group location
	if assert.Equal(t, env["location"].(string), outputs["resource_group_location"].(string)) {
		t.Logf("Resource group location test PASSED. Expected resource group location %s, got %s.", env["location"].(string), outputs["resource_group_location"].(string))
	} else {
		t.Errorf("Resource group location test FAILED. Expected resource group location %s, got %s.", env["location"].(string), outputs["resource_group_location"].(string))
	}

	// Test private dns zones
	if privateDnsZones, ok := inputs["private_dns_zones"].([]interface{}); ok && privateDnsZones != nil {
		for i := 0; i < len(privateDnsZones); i++ {
			expectedPrivateDnsZoneName := privateDnsZones[i].(string)
			if assert.Contains(t, outputs["private_dns_zone_names"].([]interface{}), expectedPrivateDnsZoneName) {
				t.Logf("Private DNS zone name test PASSED. Expected private DNS zone name %s, got %s.", expectedPrivateDnsZoneName, outputs["private_dns_zone_names"].([]interface{}))
			} else {
				t.Errorf("Private DNS zone name test FAILED. Expected private DNS zone name %s, got %s.", expectedPrivateDnsZoneName, outputs["private_dns_zone_names"].([]interface{}))
			}
		}
	} else {
		t.Errorf("Private DNS zones input is not properly configured in inputs.yaml")
	}

	// Test public ip
	expectedPublicIpName := az["prefix"].(string) + "-" + env["environment"].(string) + "-" + env["location_short"].(string) + "-" + inputs["public_ip_name"].(string)
	if override, exists := inputs["public_ip_name_override"]; exists && override != nil {
		expectedPublicIpName = override.(string)
	}
	// Split the public IP ID by "/" and get the last segment
	publicIpIdParts := strings.Split(outputs["public_ip_id"].(string), "/")
	actualPublicIpName := publicIpIdParts[len(publicIpIdParts)-1]

	if assert.Equal(t, expectedPublicIpName, actualPublicIpName) {
		t.Logf("Public IP name test PASSED. Expected public IP name %s, got %s.", expectedPublicIpName, actualPublicIpName)
	} else {
		t.Errorf("Public IP name test FAILED. Expected public IP name %s, got %s.", expectedPublicIpName, actualPublicIpName)
	}

	// Test virtual network names
	if virtualNetworks, ok := inputs["virtual_networks"].(map[string]interface{}); ok && virtualNetworks != nil {
		for _, network := range virtualNetworks {
			if vnet, ok := network.(map[string]interface{}); ok {
				expectedVnetName := vnet["name"].(string)
				if override, exists := vnet["name_override"]; exists && override != nil {
					expectedVnetName = override.(string)
				}
				if assert.Contains(t, outputs["virtual_networks_names"].([]interface{}), expectedVnetName) {
					t.Logf("Virtual network name test PASSED. Expected virtual network name %s, got %s.", expectedVnetName, outputs["virtual_networks_names"].([]interface{}))
				} else {
					t.Errorf("Virtual network name test FAILED. Expected virtual network name %s, got %s.", expectedVnetName, outputs["virtual_networks_names"].([]interface{}))
				}
			}
		}
	} else {
		t.Errorf("Virtual networks input is not properly configured in inputs.yaml")
	}

	// // Get the state in json format
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
