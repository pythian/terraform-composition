#!/bin/bash
set -euo pipefail

function exit_with_msg {
  echo "${1}"
  exit 1
}

while [ $# -gt 0 ]; do
  case "${1}" in
    -h|--help)
      echo "Usage:"
      echo "$0 \\"
      echo " [-h|--help]"
      echo "  -v|--version-file <path>"
      exit 0
      ;;
    -v|--version-file)
      VERSIONS_FILE="${2}"
      shift
      ;;
    *)
      exit_with_msg "Error: Invalid argument '${1}'."
  esac
  shift
done

readonly GO_INSTALL_DIR="/usr/local"
mkdir -p "$GO_INSTALL_DIR"

# Make sure we have write permissions to target directory before downloading
if [ ! -w "$GO_INSTALL_DIR" ] ; then
	>&2 echo "User does not have write permission to folder: ${GO_INSTALL_DIR}"
	exit 1
fi

# Get the directory where the script is located
readonly SCRIPT_DIR="$(dirname $0)"

# Get the operating system identifier.
# May be one of "linux", "darwin", "freebsd" or "openbsd".
OS_IDENTIFIER="${1:-}"
if [[ -z "$OS_IDENTIFIER" ]]; then
	# POSIX compliant OS detection
	OS_IDENTIFIER=$(uname -s | tr '[:upper:]' '[:lower:]')
	>&2 echo "Detected OS Identifier: ${OS_IDENTIFIER}"
fi
readonly OS_IDENTIFIER

# Determine the version of go to install
if [[ -z VERSIONS_FILE ]]; then
    VERSIONS_FILE="${SCRIPT_DIR}/../versions.yaml"
fi
readonly VERSIONS_FILE
>&2 echo "Reading $VERSIONS_FILE"

readonly GO_VERSION="$(cat $VERSIONS_FILE | grep '^golang_runtime_version: ' | awk -F':' '{gsub(/^[[:space:]]*["]*|["]*[[:space:]]*$/,"",$2); print $2}')"
if [[ -z "$GO_VERSION" ]]; then
	>&2 echo 'Unable to find version number'
	exit 1
fi

# Install go
readonly GO_BIN="${GO_INSTALL_DIR}/go"
cd "$(mktemp -d)"
wget "https://go.dev/dl/go${GO_VERSION}.${OS_IDENTIFIER}-amd64.tar.gz" -O go.tar.gz
rm -rf "${GO_BIN}" || echo "go is not installed."
tar -C "$GO_INSTALL_DIR" -xzf go.tar.gz
export PATH=$PATH:$GO_BIN/bin
chmod +x "${GO_BIN}"

# Cleanup
go version
rm go.tar.gz
