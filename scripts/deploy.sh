#!/usr/bin/env sh

# Exit in case of error
set -e

DOMAIN=${DOMAIN} \
PATH_PREFIX=${PATH_PREFIX} \
TRAEFIK_TAG=${TRAEFIK_TAG} \
STACK_NAME=${STACK_NAME} \
TAG=${TAG} \
docker-compose \
-f docker-compose.shared.base-images.yml \
-f docker-compose.shared.depends.yml \
-f docker-compose.deploy.env.yml \
-f docker-compose.deploy.images.yml \
-f docker-compose.deploy.command.yml \
-f docker-compose.deploy.labels.yml \
-f docker-compose.deploy.jobs.yml \
-f docker-compose.deploy.config.yml \
-f docker-compose.deploy.ports.yml \
-f docker-compose.deploy.networks.yml \
-f docker-compose.deploy.volumes-placement.yml \
config > docker-stack.yml

docker-auto-labels docker-stack.yml

docker stack deploy -c docker-stack.yml --with-registry-auth ${STACK_NAME}
