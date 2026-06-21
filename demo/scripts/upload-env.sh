#!/bin/bash

# Verificar se os arquivos locais existem
if [ ! -f "frontend/.env.prod" ]; then
    echo "Erro: frontend/.env.prod não encontrado!"
    exit 1
fi

if [ ! -f "backend/.env.prod" ]; then
    echo "Erro: backend/.env.prod não encontrado!"
    exit 1
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

echo "Criando variáveis no projeto root/teste..."

# Função para fazer o upload da variável como arquivo
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

upload_var "FRONTEND_ENV_PROD" "frontend/.env.prod"
upload_var "BACKEND_ENV_PROD" "backend/.env.prod"

echo "Pronto! Variáveis injetadas no GitLab com sucesso!"
