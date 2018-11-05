#!/bin/bash

set -ev

IMAGE_NAME="${DOCKERHUB_USERNAME}/${DOCKERHUB_REPO}"

docker build -t ${IMAGE_NAME} base/
docker login -u ${DOCKERHUB_USERNAME} -p ${DOCKERHUB_PASSWORD}
docker push ${IMAGE_NAME}