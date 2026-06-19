## Plano básico de desenvolvimento:

### Escopo:

- CRUD de livros utilizando tecnologias web

### Exigencias mínimas:

- CRUD de Livro, Autor e Assunto (Categoria) com pelo menos um relatório utilizando algum componente de relatório (Crystal, ReportView ou outro) alimentado por uma view.
- Testes unitários com coverage mínimo de 99%.
- Tratamento de erros bem estruturado.
- Validações e formatação de valores.

### Decisões:

- Backend PHP com Laravel.
- Frontend Next.js + Bootstrap.
- Base de dados postgres.
- Documentação l5-swagger no Laravel.
- Testes com PHPUnit integrado ao Laravel.
- Geração de relatórios Maatwebsite\Excel

### Considerações:

- Foco no objetivo de criar um crud básico bem estruturado e bem documentado evitando "overengeenering".

### Requisitos:

- CRUD de autores:
  - Formulário com campos CodAu (pk, autoincrement) e Nome (varchar(40))
- CRUD de Assuntos:
  - Formulário com campos CodAs (pk, autoincrement) e Descricao (varchar(20))
- CRUD de Livros:
  - Formulário com campos CodL (pk, autoincrement), Titulo (varchar(20)), Editora (varchar(40)), Edicao (int), AnoPublicacao (SMALLINT\*)
- Relatórios:
- Página de relatório de livros com filtros por Autor, Assunto, Ano de publicacao, Editora e Edicao permitindo a combinação de multiplos filtros e exportação para excel.

### Fluxo:

- Cadastros:
  - Usuário cadastra autores -> usuário cadastra assuntos -> usuário cadastra livros utilizando os dados cadastrados previamente em autores e assuntos.
- Relatório:
  - (Com pelo menos 1 livro cadastrado pelo fluxo de cadastros) usuário filtra a seleção -> clica em gerar relatório -> o download do excel inicia automaticamente.

> \*O requisito original era varchar(4) mas o inteiro funciona melhor para indexação.

### Próximos passos na evolução do projeto:

- Preencimento automatico utilizando busca pelo código isbn em uma api aberta como openlibrary.
- Serviço python para leitura código de barras do isbn na imagem da capa do livro com fallback para OCR quando o código estiver disponível.

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


