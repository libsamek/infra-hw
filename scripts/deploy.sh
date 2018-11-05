#!/bin/bash

set -ev

if [ "$1" = "Validator" ]; then
    echo "Deploying Validator Docker image ..."
    REPO_NAME=$VALIDATOR_REPO_NAME
    DEPLOY_URL=$DEPLOY_URL_VALIDATOR
else
    echo "Deploying Uploader Docker image ..."
    REPO_NAME=$UPLOADER_REPO_NAME
    DEPLOY_URL=$DEPLOY_URL_UPLOADER
fi

docker login -u _ -p $REPO_PASSWORD $IMAGE_REPO
docker pull $REPO_NAME

IMAGE_ID=$(docker inspect $REPO_NAME --format="{{.Id}}")

DEPLOY_STATUS=$(curl -s -X PATCH $DEPLOY_URL \
  -d "{
  \"updates\": [
    {
      \"type\": \"web\",
      \"docker_image\": \"${IMAGE_ID}\"
    }
  ]
}" \
  -H "Authorization: Bearer ${REPO_PASSWORD}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/vnd.heroku+json; version=3.docker-releases")

echo $DEPLOY_STATUS

if [ $(echo $DEPLOY_STATUS | jq -r '.[0].app.id' 2>&1) = "not_found" ]; then
    echo "Deploy has failed!"

    test 1 -eq 2 # safely fail
fi
