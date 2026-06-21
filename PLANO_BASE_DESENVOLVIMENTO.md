## Plano básico de desenvolvimento:

### Escopo:

- CRUD de livros utilizando tecnologias web

### Base visual
  - https://www.figma.com/proto/w6oLScKA8GRxwVhKAVaoWZ/Bootstrap-5-Design-System---UI-Kit--Community-?node-id=6502-1489&t=3DJHePQgB1963SGX-1
  
> protótipo com telas base para referencia visual no desenvolvimento (Cadastro de livros e relatório)

### Exigencias mínimas:

- CRUD de Livro, Autor e Assunto com pelo menos um relatório utilizando algum componente de relatório (Crystal, ReportView ou outro) alimentado por uma view.
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
  - Formulário com campos CodL (pk, autoincrement), Titulo (varchar(20)), Editora (varchar(40)), Edicao (int), AnoPublicacao (SMALLINT\*) e Preço (decimal(10,2))
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
