#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
PROJECT_ROOT="$DIR/../.."

# Criar .env.prod se não existirem
if [ ! -f "$PROJECT_ROOT/frontend/.env.prod" ]; then
    echo "Aviso: frontend/.env.prod não encontrado. Criando a partir do .env.example..."
    cp "$PROJECT_ROOT/frontend/.env.example" "$PROJECT_ROOT/frontend/.env.prod" || touch "$PROJECT_ROOT/frontend/.env.prod"
fi

if [ ! -f "$PROJECT_ROOT/backend/.env.prod" ]; then
    echo "Aviso: backend/.env.prod não encontrado. Criando a partir do .env.example..."
    cp "$PROJECT_ROOT/backend/.env.example" "$PROJECT_ROOT/backend/.env.prod" || touch "$PROJECT_ROOT/backend/.env.prod"
fi

echo "Gerando Personal Access Token temporário via gitlab-rails (isso pode levar 2 minutinhos)..."
PAT=$(docker exec -i gitlab-demo gitlab-rails runner "
user = User.find_by_username('root')
token = user.personal_access_tokens.create(scopes: ['api'], name: 'Temp Env Token', expires_at: 1.day.from_now)
puts token.token
")

if [ -z "$PAT" ]; then
    echo "Erro ao gerar PAT"
    exit 1
fi

echo "Verificando se o projeto 'teste' já existe..."
HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" --header "PRIVATE-TOKEN: $PAT" "http://localhost:8090/api/v4/projects/root%2Fteste")

if [ "$HTTP_STATUS" == "404" ]; then
    echo "Criando o projeto 'teste'..."
    curl -s -o /dev/null --request POST --header "PRIVATE-TOKEN: $PAT" \
         --data "name=teste&visibility=private" \
         "http://localhost:8090/api/v4/projects"
    echo "Projeto criado!"
else
    echo "Projeto já existe."
fi

echo "Injetando variáveis de ambiente no projeto..."

upload_var() {
    local key=$1
    local file=$2
    local content=$(cat "$file")
    
    # Tenta criar (POST)
    local res=$(curl -s -o /dev/null -w "%{http_code}" --request POST --header "PRIVATE-TOKEN: $PAT" \
         --form "key=$key" --form "value=$content" --form "variable_type=file" \
         "http://localhost:8090/api/v4/projects/root%2Fteste/variables")
         
    if [ "$res" == "400" ]; then
        # Se já existir, atualiza (PUT)
        curl -s -o /dev/null --request PUT --header "PRIVATE-TOKEN: $PAT" \
             --form "value=$content" --form "variable_type=file" \
             "http://localhost:8090/api/v4/projects/root%2Fteste/variables/$key"
    fi
    echo "Variável $key configurada!"
}

upload_var "FRONTEND_ENV_PROD" "$PROJECT_ROOT/frontend/.env.prod"
upload_var "BACKEND_ENV_PROD" "$PROJECT_ROOT/backend/.env.prod"

echo "Pronto! Variáveis configuradas com sucesso no GitLab!"
