BUCKET="pythian-k6-scenario-data"

install-docker-compose:
	@echo 'Installing docker-compose'
	chmod +x scripts/install_docker_compose.sh && ./scripts/install_docker_compose.sh

retrieve-data-csv:
	@echo 'Download data from storage account into local data.csv'
	gsutil cp ${BUCKET}/origin-destination-list-v4.csv ./scenarios/data.csv

base-load-test:
	@echo 'Running Base Load Test Scenario'
	docker compose run --rm k6 run /scripts/1-base-load-test-scenario.js

stress-load-test:
	@echo 'Running Stress Load Test Scenario'
	docker compose run --rm k6 run /scripts/2-stress-load-test-scenario.js

non-regular-load-test:
	@echo 'Running Stress Load Test Scenario'
	docker compose run --rm k6 run /scripts/3-non-regular-load-test-scenario.js
