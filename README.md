# Infra HW

## Technologies

- PHP 7.2.10 with Slim Framework
- Docker containers
- Dockerhub.com for hosting image base
- Travis CI for continuous integration and delivery
- Heroku.com for hosting and continuous delivery

# Services

There are two services, which are intended to be completely independent for one another. Both are built using PHP 7.2.10
and Composer packages. Both services are running in separate Docker container, which originates from the same base. The
only difference, from container point of view, is code. Unit tests are run and implemented using PHPUnit.

# Continuous integration and delivery

CI and CD has stages:
- build docker base image
- build docker service images
- test
- deploy

# Build docker base image

In this stage we build Docker image base image, which is used later on for building Docker service images. Base image
is only built when there is some necessary change to be made, eg. Dockerfile was updated or a script was modified. At
the end of the stage image is pushed to dockerhub.com repository for later use.

# Build docker service images

When we have base image built it's time to put actual code inside them. This happens in this stage. After build is
complete we push docker images to repository.heroku.com.

# Test

Tests are run in the previously built container images. That way we have identical environment that will be used in
production.

# Deploy

If all goes well we do a deploy in this stage. Deploy is done by releasing image on Heroku.com.

# Configuration

Configuration for build process is stored in environment variables on travis-ci.org. Environment variables for operation
are stored on heroku.com.
