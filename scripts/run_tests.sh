#!/bin/bash

set -ev

if [ "$1" = "Validator" ]; then
    echo "Running tests for Validator Docker image ..."
    REPO_NAME=$VALIDATOR_REPO_NAME
    DIR="${PWD}/Validator"
else
    echo "Running tests for Uploader Docker image ..."
    REPO_NAME=$UPLOADER_REPO_NAME
    DIR="${PWD}/Uploader"
fi

docker login -u _ -p $REPO_PASSWORD $IMAGE_REPO
docker pull $REPO_NAME

docker run -v $DIR/phpunit.xml.dist:/www/phpunit.xml.dist -v $DIR/tests:/www/tests --entrypoint /www/vendor/bin/phpunit $REPO_NAME --testdox
