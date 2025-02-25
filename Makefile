.PHONY: help
help:
	@echo 'make <target>'
	@echo ''
	@echo 'Targets:'
	@echo ''
	@echo '    help          Show this help'
	@echo '    pre-commit    Run pre-commit checks'
	@echo ''
	@echo '    az_clean			Clean up state files'
	@echo '    az_configure	Configure the deployment'
	@echo '    az_deploy		Deploy configured resources'
	@echo '    az_init		  Initialize modules, providers'
	@echo '    az_install		Install Terraform'
	@echo '    az_lint		  Run linters'
	@echo '    az_plan		  Show deployment plan'
	@echo '    az_test		  Run tests'
	@echo ''
	@echo '    gcp_clean     Clean up state files'
	@echo '    gcp_configure Configure the deployment'
	@echo '    gcp_deploy    Deploy configured resources'
	@echo '    gcp_init      Initialize modules, providers'
	@echo '    gcp_install   Install Terraform'
	@echo '    gcp_lint      Run linters'
	@echo '    gcp_plan      Show deployment plan'
	@echo '    gcp_test      Run tests'
	@echo ''

.PHONY: pre-commit
pre-commit:
	@pre-commit run -a

.PHONY: az_clean
az_clean:
	@cd az/resource-group && rm -rf .terraform *.tfstate* .terraform.lock.hcl
	@cd az/resource-group/test && rm -f go.mod go.sum

.PHONY: az_configure
az_configure:
	@cd az && ./scripts/configure.sh -a 00000000-0000-0000-0000-000000000000 -e example -o kpeder -p us-east1 -s us-central1 -t devops

.PHONY: az_deploy
az_deploy: az_configure az_init
	@cd az/resource-group/test && go test -v

.PHONY: az_init
az_init: az_configure
	@cd az/resource-group && terraform init
	@cd az/resource-group/test && go mod init resource_group_test.go; go mod tidy

.PHONY: az_install
az_install:
	@chmod +x ./scripts/install_terraform.sh
	@sudo ./scripts/install_terraform.sh -v ./az/versions.yaml

.PHONY: az_lint
az_lint: az_configure az_init
	@cd az/resource-group/test && golangci-lint run --print-linter-name --verbose resource-group_test.go

.PHONY: az_plan
az_plan: az_configure az_init
	@cd az/resource-group && terraform plan

.PHONY: az_test
az_test: az_configure az_init
	@cd az/resource-group/test && go test -v -destroy

.PHONY: gcp_clean
gcp_clean:
	@cd gcp/project && rm -rf .terraform *.tfstate* .terraform.lock.hcl
	@cd gcp/project/test && rm -f go.mod go.sum

.PHONY: gcp_configure
gcp_configure:
	@cd gcp && ./scripts/configure.sh -e example -m US -o kpeder -p us-east1 -s us-central1 -t devops

.PHONY: gcp_deploy
gcp_deploy: gcp_configure gcp_init
	@cd gcp/project/test && go test -v

.PHONY: gcp_init
gcp_init: gcp_configure
	@cd gcp/project && terraform init
	@cd gcp/project/test && go mod init project_test.go; go mod tidy

.PHONY: gcp_install
gcp_install:
	@chmod +x ./scripts/install_terraform.sh
	@sudo ./scripts/install_terraform.sh -v ./gcp/versions.yaml

.PHONY: gcp_lint
gcp_lint: gcp_configure gcp_init
	@cd gcp/project/test && golangci-lint run --print-linter-name --verbose project_test.go

.PHONY: gcp_plan
gcp_plan: gcp_configure gcp_init
	@cd gcp/project && terraform plan

.PHONY: gcp_test
gcp_test: gcp_configure gcp_init
	@cd gcp/project/test && go test -v -destroy
