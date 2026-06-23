# Projetos PW2

Aplicacao web desenvolvida em Laravel para a atividade individual da disciplina de Programacao Web 2.

O sistema permite gerenciar projetos academicos e suas categorias, com CRUD completo, relacionamento entre tabelas, busca/filtros, menu de navegacao, layout reutilizavel em Blade e upload de imagem para os projetos. Tambem possui cadastro/login de usuarios e acompanhamento por etapas, onde podem ser registradas atualizacoes, problematicas encontradas e metodos usados para gerir o desenvolvimento.

## Requisitos atendidos

- Laravel com MVC, migrations, models, controllers e views.
- Banco de dados SQLite configurado no arquivo `database/database.sqlite`.
- CRUD de duas tabelas principais: `categorias` e `projetos`.
- Relacionamento: uma categoria possui muitos projetos; um projeto pertence a uma categoria.
- Busca e filtros por texto, categoria, status, ano letivo e periodo letivo.
- Menu de navegacao para Inicio, Projetos e Categorias.
- Layout visual reutilizavel em `resources/views/layouts/app.blade.php`.
- Reutilizacao de componentes/parciais em formularios e mensagens.
- Extra: cadastro, login e logout de usuario.
- Extra: cada usuario visualiza apenas as proprias categorias, projetos e etapas.
- Extra: configuracao de ano letivo por semestre, trimestre ou bimestre.
- Extra: filtro inicial de projetos por ano letivo e periodo atual do usuario.
- Extra: upload de imagem nos projetos usando o disco `public`.
- Extra: etapas de desenvolvimento com atualizacoes, problematicas e solucoes/metodos de gestao.

## Modelagem do banco

### users

| Campo | Tipo | Descricao |
| --- | --- | --- |
| id | integer | Chave primaria |
| name | string | Nome do usuario |
| email | string | E-mail usado no login |
| password | string | Senha criptografada |
| tipo_periodo | enum | Define se o usuario organiza o ano por semestre, trimestre ou bimestre |
| created_at / updated_at | timestamps | Controle de cadastro |

### categorias

| Campo | Tipo | Descricao |
| --- | --- | --- |
| id | integer | Chave primaria |
| user_id | foreign key | Dono da categoria |
| nome | string | Nome da categoria |
| descricao | text | Descricao opcional |
| cor | string | Cor usada na interface |
| ativo | boolean | Define se a categoria pode ser usada |
| created_at / updated_at | timestamps | Controle de cadastro |

### projetos

| Campo | Tipo | Descricao |
| --- | --- | --- |
| id | integer | Chave primaria |
| user_id | foreign key | Dono do projeto |
| categoria_id | foreign key | Relaciona com `categorias.id` |
| titulo | string | Titulo do projeto |
| responsavel | string | Nome do responsavel |
| resumo | text | Resumo do projeto |
| status | enum | Planejamento, Em andamento, Concluido ou Pausado |
| ano_letivo | integer | Ano letivo do projeto |
| periodo_letivo | integer | Semestre, trimestre ou bimestre conforme configuracao do usuario |
| data_inicio | date | Data inicial opcional |
| data_entrega | date | Prazo opcional |
| imagem | string | Caminho da imagem enviada |
| created_at / updated_at | timestamps | Controle de cadastro |

### etapa_projetos

| Campo | Tipo | Descricao |
| --- | --- | --- |
| id | integer | Chave primaria |
| projeto_id | foreign key | Relaciona com `projetos.id` |
| titulo | string | Nome da etapa do desenvolvimento |
| data_registro | date | Data da atualizacao |
| status | enum | Planejada, Em execucao, Concluida ou Bloqueada |
| descricao | text | Atualizacao realizada |
| problematica | text | Problema, dificuldade ou risco encontrado |
| metodo_gestao | text | Solucao, decisao ou tecnica usada para gerir a etapa |
| ordem | integer | Ordem de exibicao na linha do tempo |
| created_at / updated_at | timestamps | Controle de cadastro |

## Como rodar com SQLite

O projeto usa SQLite, entao nao precisa abrir XAMPP, MySQL ou phpMyAdmin. O banco fica em `database/database.sqlite`.

```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
npm run build
php artisan serve
```

Depois acesse:

```text
http://127.0.0.1:8000
```

Configuracao esperada no `.env`:

```env
DB_CONNECTION=sqlite
```

Usuario de demonstracao criado pelo seeder:

```text
E-mail: admin@pw2.local
Senha: password
```

## Comandos de verificacao

```bash
php artisan test
npm run build
```
