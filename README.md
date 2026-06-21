
# Subindo ambientes

## Subindo ambiente de desenvolvimento local

### Requisitos
 - Docker com Docker compose
 - NodeJs (Recomendado nvm)
 - Wsl (Opcional)

### Comandos:

### Copie os env.example

```bash
  cp backend/.env.example backend/.env && cp frontend/.env.example frontend/.env.local
```

### Para subir o backend:

```bash
  docker compose up -d
```

### Migrations (Cria tabelas do banco de dados)

```bash
  docker exec -it app php artisan migrate
```

### Gerar Documentação (l5-swagger, opcional)

```bash
  docker exec -it app php artisan l5-swagger:generate
```
> Acessível em <BaseApi>/api/documentation
> Documentação do l5-swagger: https://github.com/DarkaOnLine/L5-Swagger/wiki

### Seeders (Gera dados falsos para desenvolvimento, opcional)

```bash
  docker exec -it app php artisan db:seed
```

### Gerar chave de aplicação (APP_KEY) no .env (opcional)

```bash
  docker exec -it app php artisan key:generate
```

### Para subir o frontend

```bash
  cd frontend && npm install && npm run dev
```

## Subindo ambiente de produção

### Comandos:

### Copie os env.example

```bash
  cp backend/.env.example backend/.env.prod && cp frontend/.env.example frontend/.env.prod
```

```bash
  docker compose -f docker-compose.prod.yml up -d --build
```

> O .env.prod (back e front) são apenas uma opção para configurar as variáveis de ambiente mas é recomendado configurar no host como variáveis de ambiente.
> O frontend já está previsto neste docker-compose.prod.yml, ele será buildado e servido em um container dedicado, não sendo necessário subir manualmente.

### Deploy Automatizado com GitLab Local (Demonstração)

Você também pode subir o ambiente de produção de forma totalmente automatizada através de uma pipeline de CI/CD, utilizando nossa simulação de GitLab Local.

Isso levantará os containers do GitLab, registrará um Runner local, configurará o repositório e executará os testes e o deploy de produção de forma automática ao realizar um `git push`.

👉 **[Clique aqui para ver as instruções detalhadas do Ambiente de CI/CD (GitLab Local)](./demo/README.md)**


