# concrete-cms

## Requirements
- golang: "1.24.1"
- terraform: "1.5.7"
- terraform-docs
- docker
- docker-compose

## database configuration
The file `cnx-website/public_html/application/config/database.php` is using an environment variables to receive the database connection information, these values are being set via Terraform based on the key vault secrets during deployment.

## Docker image creation
To create a new docker image based on the file sin this repository use the command (running as root) and with azure cli logged in as root

```
make website_deploy_docker
```

This will rebuild the contaienr locally and push to azure ACR
The container tag will be equal to the current date. i.e. `20250616`

## Environment deployment
The targets below deploys each one of the environents and requires to be logged on azure cli and regular user

```
# dev

make az_deploy_dev

# test

make az_deploy_test

# prd

make az_deploy_prd

```

## Load test

Navigate inside the folder `loadtest/backend`
Use the make targets based on the tests you want to run

```
# base load test to reach and specifc target simultaneous connections
make base-load-test

# more agressive test with a higher number of connections
make stress-load-test

# Growing connections in a non regular way increasing traffic time by time
make non-regular-load-test
```
