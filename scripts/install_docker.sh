#!/bin/bash
set -euo pipefail

# Check if the script is run as root
if [ "$EUID" -ne 0 ]; then
    echo "Please run as root"
    exit 1
fi
# Check if Docker is already installed
if command -v docker &> /dev/null; then
    echo "Docker is already installed"
    exit 0
fi
# Update the package database
echo "Updating package database..."
apt-get update -y
# Install required packages
echo "Installing required packages..."
apt-get install -y \
    apt-transport-https \
    ca-certificates \
    curl \
    gnupg \
    lsb-release

# Add Docker's official GPG key
echo "Adding Docker's official GPG key..."
curl -fsSL https://download.docker.com/linux/$(lsb_release -is | tr '[:upper:]' '[:lower:]')/gpg | apt-key add -
# Add Docker's official repository
echo "Adding Docker's official repository..."
echo "deb [arch=$(dpkg --print-architecture)] https://download.docker.com/linux/$(lsb_release -is | tr '[:upper:]' '[:lower:]') $(lsb_release -cs) stable" > /etc/apt/sources.list.d/docker.list
# Update the package database again
echo "Updating package database again..."
apt-get update -y
# Install Docker
echo "Installing Docker..."
apt-get install -y docker-ce docker-ce-cli containerd.io
# Start Docker service
echo "Starting Docker service..."
systemctl start docker
# Enable Docker service to start on boot
echo "Enabling Docker service to start on boot..."
systemctl enable docker
# Add the current user to the Docker group
echo "Adding the current user to the Docker group..."
usermod -aG docker "$USER"
# Print Docker version
echo "Docker installation completed successfully!"
echo "Docker version:"
docker --version
# Print instructions to log out and back in
echo "Please log out and back in to apply the group changes."
