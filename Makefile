.PHONY: help
help:
	@echo 'make <target>'
	@echo ''
	@echo 'Targets:'
	@echo ''
	@echo '    help               Show this help'
	@echo '    pre-commit         Run pre-commit checks'
	@echo '    az_install         Install Terraform'
	@echo ''
	@echo 'Configure environments'
	@echo ''
	@echo '    az_configure_build Configure the deployment for build'
	@echo '    az_configure_dev   Configure the deployment for dev'
	@echo '    az_configure_test   Configure the deployment for test'
	@echo '    az_configure_prd   Configure the deployment for prd'
	@echo ''
	@echo 'All application environments'
	@echo ''
	@echo '    az_clean           Clean up state files'
	@echo '    az_deploy          Deploy configured resources'
	@echo '    az_init            Initialize modules, providers'
	@echo '    az_lint            Run linters'
	@echo '    az_plan            Show deployment plan'
	@echo '    az_test            Run tests'
	@echo ''
	@echo 'Load tests'
	@echo ''
	@echo '    az_load_test       Run load tests'
	@echo ''
	@echo 'Terraform build environment'
	@echo ''
	@echo '    az_clean_build     Clean up state files'
	@echo '    az_deploy_build    Deploy configured resources'
	@echo '    az_deploy_dev      Deploy configured resources'
	@echo '    az_deploy_test     Deploy configured resources'
	@echo '    az_deploy_prd      Deploy configured resources'
	@echo '    az_init_build      Initialize modules, providers'
	@echo '    az_lint_build      Run linters'
	@echo '    az_plan_build      Show deployment plan'
	@echo '    az_test_build      Run tests'
	@echo ''
	@echo '    website_deploy     Deploy website over FTPS'
	@echo ''

#### RUN ENTIRE PROJECT ####
.PHONY: az_project_deploy
az_project_deploy: az_deploy_build az_configure_dev az_deploy

#### BUILD ENVIRONMENT ####

.PHONY: az_clean_build
az_clean_build:
	@cd az/build && rm -rf .terraform *.tfstate* .terraform.lock.hcl
	@cd az/build/test && rm -f go.mod go.sum

.PHONY: az_configure_build
az_configure_build:
	@cd az && ./scripts/configure.sh -a 9712bfef-07af-4a61-804e-b2fa08462f70 -e build -o connexus -p centralus -ps cus -t devops -z build

.PHONY: az_deploy_build
az_deploy_build: az_init_build
	@cd az/build/test && go test -v

.PHONY: az_init_build
az_init_build: az_configure_build
	@cd az/build && terraform init
	@cd az/build/test && go mod init build_test.go; go mod tidy

.PHONY: az_lint_build
az_lint_build: az_init_build
	@cd az/build/test && golangci-lint run --verbose build_test.go

.PHONY: az_plan_build
az_plan_build: az_init_build
	@cd az/build && terraform plan

.PHONY: az_test_build
az_test_build: az_init_build
	@cd az/build/test && go test -v -destroy

#### DEV ENVIRONMENT ####

.PHONY: az_configure_dev
az_configure_dev:
	@cd az && ./scripts/configure.sh -a 9712bfef-07af-4a61-804e-b2fa08462f70 -e dev -o connexus -p centralus -ps cus -t devops -z concrete-cms

.PHONY: az_deploy_dev
az_deploy_dev: az_configure_dev az_clean az_deploy

#### TEST ENVIRONMENT ####

.PHONY: az_configure_test
az_configure_test:
	@cd az && ./scripts/configure.sh -a 9712bfef-07af-4a61-804e-b2fa08462f70 -e test -o connexus -p centralus -ps cus -t devops -z concrete-cms

.PHONY: az_deploy_test
az_deploy_test: az_configure_test az_clean az_deploy

#### PROD ENVIRONMENT ####

.PHONY: az_configure_prd
az_configure_prd:
	@cd az && ./scripts/configure.sh -a 9712bfef-07af-4a61-804e-b2fa08462f70 -e prd -o connexus -p centralus -ps cus -t devops -z concrete-cms

.PHONY: az_deploy_prd
az_deploy_prd: az_configure_prd az_clean az_deploy

#### DEPLOYMENTS ALL APPLICATION ENVIRONMENTS ####

.PHONY: az_clean
az_clean:
	@cd az/concrete-cms && rm -rf .terraform *.tfstate* .terraform.lock.hcl
	@cd az/concrete-cms/test && rm -f go.mod go.sum

.PHONY: az_deploy
az_deploy: az_init
	@cd az/concrete-cms/test && go test -v

.PHONY: az_init
az_init:
	@cd az/concrete-cms && terraform init
	@cd az/concrete-cms/test && go mod init concrete-cms_test.go; go mod tidy

.PHONY: az_lint
az_lint: az_init
	@cd az/concrete-cms/test && golangci-lint run --verbose concrete-cms_test.go

.PHONY: az_plan
az_plan: az_init
	@cd az/concrete-cms && terraform plan

.PHONY: az_test
az_test: az_init
	@cd az/concrete-cms/test && go test -v -destroy

#### LOAD TESTS ####

.PHONY: az_load_test
az_load_test:
	@echo "To be implemented with k6"

#### SHARED ####

.PHONY: pre-commit
pre-commit:
	@pre-commit run -a


.PHONY: az_install
az_install:
	@chmod +x ./scripts/install_terraform.sh
	@sudo ./scripts/install_terraform.sh -v ./az/versions.yaml
	@sudo ./scripts/install_go.sh -v ./az/versions.yaml

#### WEBSITE DEPLOY ####
.PHONY: website_deploy_zip
website_deploy_zip:
	@echo 'zip the package'
	@cd cnx-website/public-html && zip -r ../cnx-website.zip .
	@echo 'use website deployment via azure cli'
	@az webapp deploy --resource-group cnx-dev-cus-website --name cnx-dev-cus-website-connexusenergy --src-path cnx-website/cnx-website.zip

# @az webapp config appsettings set --resource-group cnx-dev-cus-website --name cnx-dev-cus-website-connexusenergy --settings WEBSITE_RUN_FROM_PACKAGE="1"

.PHONY: website_deploy_docker
website_deploy_docker:
	@echo 'docker build'
	@tag=$(shell date +%Y%m%d) && cd cnx-website && sudo docker build -t cnx-website:$$tag .
	@echo 'docker login'
	@sudo az acr login --name cnxbuildcusregistry
	@echo 'docker push'
	@tag=$(shell date +%Y%m%d) && sudo docker tag cnx-website:$$tag cnxbuildcusregistry.azurecr.io/cnx-website:$$tag
	@tag=$(shell date +%Y%m%d) && sudo docker push cnxbuildcusregistry.azurecr.io/cnx-website:$$tag
