#!/bin/bash

# Set the HOST value here
HOST="192.168.33.189"

# Load environment variables from both .env files

if [ -f "createmore_admin/.env" ]; then
    export $(grep -v '^#' createmore_admin/.env | xargs)
fi

echo "🚀 Setting HOST value to $HOST in both .env files..."

# Replace 'localhost' with the new HOST in both .env files
sed -i "s|http://localhost|http://$HOST|g" createmore_admin/.env

# Verify replacement
echo "✅ Updated .env files:"
grep "http://$HOST" createmore_admin/.env

# Pull the latest code
echo "🔄 Pulling latest changes from GitHub..."
git fetch origin
git merge origin/dev

# Stop and remove existing containers
echo "🛑 Stopping and removing existing containers..."
docker-compose down

# Remove old images
echo "🗑️ Cleaning up old Docker images..."
docker image prune -af

# Rebuild and deploy containers
echo "🚀 Deploying containers on $HOST..."
docker-compose up -d --build

# Show running containers
echo "✅ Deployment completed!"
docker ps