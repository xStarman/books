# Demonstração de CI/CD com GitLab Local

Este diretório contém os scripts e a infraestrutura necessária para subir uma instância local do **GitLab** e do **GitLab Runner** via Docker. O objetivo é simular um ambiente de CI/CD real para demonstração, rodando o build, os testes e o deploy automático da aplicação na sua própria máquina.

## O que acontece por debaixo dos panos?

Ao rodar o script principal, a seguinte sequência de eventos é disparada automaticamente:

1. **Subida dos Containers:** O script usa o `docker-compose.yml` para levantar o GitLab e o GitLab Runner em background.
2. **Healthcheck:** O script aguarda pacientemente a inicialização completa da API do GitLab (que pode levar alguns minutos dependendo do computador).
3. **Configuração de Remotes:** O repositório local é configurado com um *push duplo*. Isso significa que ao fazer um `git push origin main`, o seu código será enviado tanto para o GitHub quanto para esse GitLab local.
4. **Registro do Runner:** O token de registro do GitLab é gerado automaticamente e o Runner é autenticado, ficando pronto para ouvir os jobs do pipeline.

## Como iniciar

Certifique-se de que o Docker está rodando e execute o script na raiz do repositório:

```bash
./demo/start-demo.sh
```

Aguarde até receber a mensagem de que os remotes foram configurados e o Runner registrado com sucesso.

## Como testar o CI/CD na prática

> [!IMPORTANT]
> **Sobre o primeiro acesso à interface do GitLab:** Ao logar pela primeira vez, você verá um painel vazio pedindo para criar o seu primeiro projeto. **Não crie o projeto manualmente e não clique em "Skip"** (isso pode causar um bug de sessão que desloga sua conta). 
> O GitLab possui um recurso de *Push to Create*. Portanto, basta realizar o primeiro `git push` conforme as instruções abaixo que o repositório `teste` será criado automaticamente para você em background, já populado com o seu código!

Com tudo rodando, basta fazer qualquer alteração no código e realizar um commit normalmente:

```bash
git add .
git commit -m "Testando o pipeline local"
git push origin main
```

**O que vai acontecer?**
- O código será enviado para a sua máquina virtual do GitLab.
- O pipeline configurado no `.gitlab-ci.yml` na raiz do projeto será iniciado.
- Se você alterou arquivos do `frontend/`, o **build** do Next.js será testado.
- Se você alterou arquivos do `backend/`, os **testes** do Laravel serão executados.
- Como o push foi feito na branch `main`, o job de **deploy** será executado ao final. Ele utilizará o `/var/run/docker.sock` para recriar e subir os seus containers da aplicação usando o `docker-compose.prod.yml`.

## Acessos e Portas

Para evitar conflitos com a API do Laravel (que usa a porta 8080), o GitLab local foi configurado na porta `8090`.

- **GitLab UI:** [http://localhost:8090](http://localhost:8090) (Usuário: `root` | Senha: `Sup3rS3cr3t#2026!`)
- **Frontend (Após deploy):** [http://localhost:3000](http://localhost:3000)
- **Backend API (Após deploy):** [http://localhost:8080](http://localhost:8080)
