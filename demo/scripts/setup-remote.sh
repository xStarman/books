#!/bin/bash

# A senha do root definida no docker-compose.yml
ROOT_PASSWORD="Sup3rS3cr3t#2026!"

GITLAB_URL="http://root:Sup3rS3cr3t%232026%21@localhost:8090/root/teste.git"

echo "Configurando remotes de push duplo..."

GITHUB_URL=$(git remote get-url origin 2>/dev/null)

if [ -z "$GITHUB_URL" ]; then
    echo "Nenhum remote origin configurado."
    exit 1
fi

echo "URL origin: $GITHUB_URL"

if ! git remote | grep -q gitlab-local; then
    git remote add gitlab-local "$GITLAB_URL"
    echo "Remote 'gitlab-local' adicionado."
fi

git remote set-url --delete --push origin "$GITHUB_URL" 2>/dev/null || true
git remote set-url --delete --push origin "$GITLAB_URL" 2>/dev/null || true

git remote set-url --add --push origin "$GITHUB_URL"
git remote set-url --add --push origin "$GITLAB_URL"

echo "Remotes atualizados"
