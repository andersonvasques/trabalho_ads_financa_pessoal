# API de Finanças Pessoais

API REST para controle de finanças pessoais. Cada usuário cadastra suas **contas** (corrente, poupança, carteira, investimento), registra **lançamentos** de receitas e despesas classificados por **categorias** e define **orçamentos** mensais de gasto por categoria.

A API calcula o saldo atual de cada conta e quanto já foi gasto de cada orçamento no mês, e garante que cada usuário só acesse os próprios dados.

Trabalho final da disciplina **Arquitetura e Desenvolvimento de APIs** (UNINTER).

## Tecnologias

| Item | Tecnologia |
|---|---|
| Linguagem | PHP 8.4 |
| Framework | Laravel 12 |
| Banco de dados | Microsoft SQL Server 2025 (Developer, via Docker) |
| ORM | Eloquent (Laravel) |
| Autenticação | Laravel Sanctum (token Bearer) |
| Documentação | Scramble (OpenAPI / Swagger) |
| Infraestrutura | Docker, Docker Compose e Nginx |

## Pré-requisitos

- [Docker](https://docs.docker.com/get-docker/) com o Docker Compose
- Git

Não é preciso ter PHP, Composer nem SQL Server instalados na máquina: tudo roda nos containers.

## Instalação e execução

**1. Clone o repositório**

```bash
git clone https://github.com/andersonvasques/trabalho_ads_financa_pessoal.git
cd trabalho_ads_financa_pessoal
```

**2. Crie o arquivo de ambiente**

```bash
cp .env.example .env
```

Abra o `.env` e preencha `DB_PASSWORD` e `MSSQL_SA_PASSWORD` com **a mesma senha**. O SQL Server exige senha forte: pelo menos 8 caracteres, com letra maiúscula, minúscula, número e símbolo (ex.: `Financa@2026`). As demais variáveis do banco já vêm configuradas para o container.

**3. Suba os containers**

```bash
docker compose up -d --build
```

São criados três containers: `php` (PHP-FPM com o driver do SQL Server), `nginx` (servidor web na porta 8080) e `sqlserver` (banco na porta 1433).

> Se o seu usuário no Linux não tiver UID/GID 1000, rode `docker compose build --build-arg UID=$(id -u) --build-arg GID=$(id -g)` antes, para evitar problemas de permissão em `storage/`.

**4. Instale as dependências e gere a chave da aplicação**

```bash
docker compose exec php composer install
docker compose exec php php artisan key:generate
```

**5. Crie o banco de dados**

O container do SQL Server não cria o banco automaticamente. Aguarde alguns segundos após subir os containers e rode (troque `SUA_SENHA` pela senha do `.env`):

```bash
docker compose exec sqlserver /opt/mssql-tools18/bin/sqlcmd -S localhost -U sa -P "SUA_SENHA" -C -Q "CREATE DATABASE financa_pessoal"
```

Também é possível criar o banco por um cliente SQL (DBeaver, Azure Data Studio, SSMS), conectando em `localhost,1433` com o usuário `sa`.

**6. Crie as tabelas e os dados iniciais**

```bash
docker compose exec php php artisan migrate --seed
```

As migrations criam as tabelas e o seeder cadastra as **13 categorias padrão** (Alimentação, Moradia, Salário etc.) e um **usuário de teste**.

**Pronto.** A API fica disponível em `http://localhost:8080/api`.

## Documentação (Swagger / OpenAPI)

Com `APP_ENV=local`, a documentação interativa gerada pelo Scramble fica em:

- **Interface:** http://localhost:8080/docs/api
- **JSON OpenAPI:** http://localhost:8080/docs/api.json

Pela interface é possível testar todos os endpoints. Para as rotas protegidas, informe o token no campo de autenticação (Bearer).

## Autenticação

A API usa tokens do **Laravel Sanctum**:

1. Faça `POST /api/register` (novo usuário) ou `POST /api/login` e copie o `token` da resposta.
2. Envie o token nas demais requisições no cabeçalho `Authorization: Bearer <token>`.
3. `POST /api/logout` revoga o token atual.

Sem token válido, as rotas protegidas respondem **401**.

**Para testar rapidamente**, o seeder cria um usuário local de desenvolvimento: e-mail `test@example.com`, senha `password`. Ele existe apenas no banco criado pelo `--seed`.

## Endpoints

Todas as rotas têm o prefixo `/api`. Exceto `register` e `login`, todas exigem token.

| Método | Endpoint | Descrição |
|---|---|---|
| POST | `/register` | Cadastra usuário e retorna token |
| POST | `/login` | Autentica e retorna token |
| POST | `/logout` | Revoga o token atual |
| GET | `/me` | Dados do usuário autenticado |
| GET | `/categorias` | Lista categorias padrão + do usuário (`?tipo_categoria=receita\|despesa`) |
| GET | `/categorias/{id}` | Consulta uma categoria |
| POST | `/categorias` | Cria categoria |
| PUT | `/categorias/{id}` | Atualiza categoria (somente as do usuário) |
| DELETE | `/categorias/{id}` | Exclui categoria (somente as do usuário) |
| GET | `/contas` | Lista contas com saldo atual (`?cad_ativo=1`) |
| GET | `/contas/{id}` | Consulta uma conta |
| POST | `/contas` | Cria conta |
| PUT | `/contas/{id}` | Atualiza conta |
| DELETE | `/contas/{id}` | Exclui conta |
| GET | `/lancamentos` | Lista lançamentos paginados (filtros abaixo) |
| GET | `/lancamentos/{id}` | Consulta um lançamento |
| POST | `/lancamentos` | Cria lançamento |
| PUT | `/lancamentos/{id}` | Atualiza lançamento |
| DELETE | `/lancamentos/{id}` | Exclui lançamento |
| GET | `/orcamentos` | Lista orçamentos com valor gasto e restante (`?ano=&mes=`) |
| GET | `/orcamentos/{id}` | Consulta um orçamento |
| POST | `/orcamentos` | Cria orçamento |
| PUT | `/orcamentos/{id}` | Atualiza orçamento |
| DELETE | `/orcamentos/{id}` | Exclui orçamento |

**Filtros de `/lancamentos`:** `ano` + `mes`, `id_conta`, `id_categoria`, `tipo_lancamento` (`receita`/`despesa`), `situacao` (`pago`/`pendente`) e `por_pagina` (padrão 20).

### Códigos de resposta

| Código | Quando |
|---|---|
| 200 | Consulta ou atualização realizada |
| 201 | Recurso criado |
| 204 | Recurso excluído (sem corpo) |
| 401 | Token ausente ou inválido |
| 403 | Tentativa de alterar/excluir uma categoria padrão do sistema |
| 404 | Recurso inexistente ou de outro usuário |
| 422 | Dados inválidos (a resposta lista os erros por campo) |

## Exemplo de requisição

**Login:**

```bash
curl -X POST http://localhost:8080/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email": "test@example.com", "password": "password"}'
```

**Criar um lançamento** (usando o token retornado no login e o id de uma conta já criada em `POST /api/contas`; a categoria 1 é "Alimentação"):

```bash
curl -X POST http://localhost:8080/api/lancamentos \
  -H "Authorization: Bearer SEU_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "id_conta": 1,
    "id_categoria": 1,
    "descricao_lancamento": "Mercado do mês",
    "valor_lancamento": 250.25,
    "data_lancamento": "2026-10-06",
    "data_pagamento_lancamento": "2026-10-06"
  }'
```

Resposta `201 Created`:

```json
{
  "data": {
    "id_lancamento": 1,
    "tipo_lancamento": "despesa",
    "descricao_lancamento": "Mercado do mês",
    "valor_lancamento": 250.25,
    "data_lancamento": "2026-10-06",
    "data_pagamento_lancamento": "2026-10-06",
    "pago": true,
    "observacao_lancamento": null,
    "conta": { "id_conta": 1, "nome_conta": "Nubank" },
    "categoria": { "id_categoria": 1, "nome_categoria": "Alimentação" }
  }
}
```

O `tipo_lancamento` não é enviado: ele é definido pela categoria escolhida. Sem `data_pagamento_lancamento`, o lançamento fica pendente e não entra no saldo da conta.

## Banco de dados

| Tabela | Conteúdo |
|---|---|
| `users` | Usuários (tabela padrão do Laravel) |
| `personal_access_tokens` | Tokens do Sanctum |
| `cad_categoria` | Categorias de receita/despesa (`id_usuario` nulo = categoria padrão) |
| `cad_conta` | Contas do usuário e saldo inicial |
| `mov_lancamento` | Receitas e despesas |
| `pla_orcamento` | Limite de gasto por categoria por mês |

Prefixos: `cad_` = cadastro, `mov_` = movimentação, `pla_` = planejamento. A estrutura é criada pelas migrations em `database/migrations`.

## Estrutura do código

```
domain/Models/
├── Autenticacao/   # login, registro, logout e "me"
├── Categoria/
├── Conta/
├── Lancamento/
├── Orcamento/
│   ├── Actions/        # regras de negócio (uma classe por operação)
│   ├── Requests/       # validação da entrada (FormRequest)
│   ├── Resources/      # formato do JSON de saída
│   ├── Orcamento.php   # model Eloquent
│   └── OrcamentoController.php
├── Enums/          # TipoMovimento, TipoConta
└── User/
```

As rotas ficam em `routes/api.php`.
