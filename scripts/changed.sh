#!/bin/bash

# This script checks for changes and sets env variables

set -ev

function changed()
{
    local NAME=$1
    local PATTERN

    if [ "$NAME" != "base" ]; then
        PATTERN="\.travis.yml|base|scripts|${NAME}"
    else
        PATTERN="\.travis.yml|$NAME"
    fi

    if git diff --name-only $TRAVIS_COMMIT_RANGE | grep -qE $PATTERN; then
        echo "Setting variable '${NAME}' to value 'do-build' ..."
        export $(echo $NAME)="do-build"
    fi
}

changed $1
