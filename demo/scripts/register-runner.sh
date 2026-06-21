#!/bin/bash

echo "Pegando o token interno do GitLab..."

REG_TOKEN=$(docker exec -i gitlab-demo gitlab-rails runner -e production "puts Gitlab::CurrentSettings.current_application_settings.runners_registration_token")

if [ -z "$REG_TOKEN" ]; then
    echo "Erro ao obter o token. Verifique se o GitLab ja iniciou."
    exit 1
fi

echo "Checando o status do runner..."
if docker exec -i gitlab-runner-demo gitlab-runner list 2>&1 | grep -q "gitlab-runner-demo"; then
    echo "Runner ja está registrado."
else
    echo "Registrando o Runner..."
    
    docker exec -i gitlab-runner-demo gitlab-runner register \
      --non-interactive \
      --url "http://gitlab-demo:8090/" \
      --clone-url "http://gitlab-demo:8090/" \
      --registration-token "$REG_TOKEN" \
      --executor "docker" \
      --docker-image "docker:latest" \
      --docker-volumes "/var/run/docker.sock:/var/run/docker.sock" \
      --docker-network-mode "demo-network" \
      --description "gitlab-runner-demo" \
      --tag-list "docker" \
      --run-untagged="true"
      
    echo "Runner registrado com sucesso."
fi

echo "instalando dependencias"

docker exec -u root gitlab-runner-demo apt-get update
docker exec -u root gitlab-runner-demo apt-get install -y docker.io docker-compose-v2 || true
