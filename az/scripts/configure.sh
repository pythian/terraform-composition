#!/bin/bash

set -e

function exit_with_msg {
  echo "${1}"
  exit 1
}

while [ $# -gt 0 ]; do
  case "${1}" in
    -a|--azuresubscriptionid)
      SUBSCRIPTION="${2}"
      shift
      ;;
    -e|--environment)
      ENVIRONMENT="${2}"
      shift
      ;;
    -h|--help)
      echo "Usage:"
      echo "$0 \\"
      echo "  -e|--environment <environment_name>"
      echo " [-h|--help]"
      echo "  -a|--azuresubscriptionid [00000000-0000-0000-0000-000000000000]"
      echo "  -o|--owner <owner>"
      echo "  -p|--primaryregion <primary_region>"
      echo "  -t|--team <owner>"
      exit 0
      ;;
    -o|--owner)
      OWNER="${2}"
      shift
      ;;
    -p|--primaryregion)
      PREGION="${2}"
      shift
      ;;
    -ps|--primaryregionshort)
      PREGION_SHORT="${2}"
      shift
      ;;
    -t|--team)
      TEAM="${2}"
      shift
      ;;
    *)
      exit_with_msg "Error: Invalid argument '${1}'."
  esac
  shift
done

if [[ -f local.az.yaml ]]; then
  PREFIX=$(cat local.az.yaml | grep ^prefix | awk -F '[: #"]+' '{print $2}')
else
  PREFIX=$(cat az.yaml | grep ^prefix | awk -F '[: #"]+' '{print $2}')
fi

[[ -z ${PREFIX} ]] && exit_with_msg "Can't locate deployment prefix. Exiting."
[[ ${#PREFIX} > 5 ]] && exit_with_msg "Prefix '${PREFIX}' is too long. Exiting."

[[ -z ${ENVIRONMENT} ]] && exit_with_msg "-e|--environment is a required parameter. Exiting."
[[ -z ${SUBSCRIPTION} ]] && exit_with_msg "-a|--azuresubscriptionid is a required parameter. Exiting."
[[ -z ${OWNER} ]] && exit_with_msg "-o|--owner is a required parameter. Exiting."
[[ -z ${PREGION} ]] && exit_with_msg "-p|--primaryregion is a required parameter. Exiting."
[[ -z ${PREGION_SHORT} ]] && exit_with_msg "-ps|--primaryregionshort is a required parameter. Exiting."
[[ -z ${TEAM} ]] && exit_with_msg "-t|--team is a required parameter. Exiting."

echo "Deployment Owner: ${OWNER}"
echo "Environment: ${ENVIRONMENT}"
echo "Azure Subscription ID: ${SUBSCRIPTION}"
echo "Name Prefix: ${PREFIX}"
echo "Primary Region: ${PREGION}"
echo "Primary Region Short: ${PREGION_SHORT}"
echo "Support Team: ${TEAM}"

cp templates/env.tpl env.yaml

sed -i -e "s:ENVIRONMENT:${ENVIRONMENT}:g" env.yaml
sed -i -e "s:SUBSCRIPTION:${SUBSCRIPTION}:g" env.yaml
sed -i -e "s:OWNER:${OWNER}:g" env.yaml
sed -i -e "s:PREFIX:${PREFIX}:g" env.yaml
sed -i -e "s:PREGION_SHORT:${PREGION_SHORT}:g" env.yaml
sed -i -e "s:PREGION:${PREGION}:g" env.yaml
sed -i -e "s:TEAM:${TEAM}:g" env.yaml
