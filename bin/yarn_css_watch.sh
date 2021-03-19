#!/usr/bin/env bash

APP_DIR="$(dirname "$(realpath "${BASH_SOURCE[0]}")")/.."
APP_TAG='meritokrat/node:latest'

docker build \
    --tag "${APP_TAG}" \
    --file "${APP_DIR}/docker/node/Dockerfile" \
    .

docker run -it --rm  -v "${APP_DIR}:/app" "${APP_TAG}" bash
#docker run -it --rm node:latest bash
