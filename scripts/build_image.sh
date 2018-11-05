#!/bin/bash

set -ev

if [ "$1" = "Validator" ]; then
    echo "Building Validator Docker image ..."
    REPO_NAME=$VALIDATOR_REPO_NAME
    DIR="Validator"
else
    echo "Building Uploader Docker image ..."
    REPO_NAME=$UPLOADER_REPO_NAME
    DIR="Uploader"
fi

docker build -t $REPO_NAME $DIR
docker tag $REPO_NAME $REPO_NAME:$TRAVIS_BUILD_NUMBER
docker login $IMAGE_REPO -u _ -p $REPO_PASSWORD
docker push $REPO_NAME:latest
docker push $REPO_NAME:$TRAVIS_BUILD_NUMBER
