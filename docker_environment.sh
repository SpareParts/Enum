#!/usr/bin/env bash
set -e
docker build . -t docker-develop
docker run -e XDEBUG_CONFIG=remote_host=192.168.2.108 -it -v ${PWD}:/app docker-develop bash