#!/bin/bash

echo "Iniciando o Gitlab..."
docker compose -f demo/gitlab/docker-compose.yml up -d

bash demo/scripts/bootstrap.sh
