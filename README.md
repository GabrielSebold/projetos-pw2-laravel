# Projetos PW2

Aplicação web desenvolvida em Laravel para a atividade individual da disciplina de Programação Web 2.

O sistema permite gerenciar projetos acadêmicos e suas categorias, com CRUD completo, relacionamento entre tabelas, busca/filtros, menu de navegação, layout reutilizável em Blade e upload de imagem para os projetos. Também possui cadastro/login de usuários e acompanhamento por etapas, onde podem ser registradas atualizações, problemáticas encontradas e métodos usados para gerir o desenvolvimento.

## Requisitos atendidos

- Laravel com MVC, migrations, models, controllers e views.
- Banco de dados SQLite configurado no arquivo `database/database.sqlite`.
- CRUD de duas tabelas principais: `categorias` e `projetos`.
- Relacionamento: uma categoria possui muitos projetos; um projeto pertence a uma categoria.
- Busca e filtros por texto, categoria e status.
- Menu de navegação para Início, Projetos e Categorias.
- Layout visual reutilizável em `resources/views/layouts/app.blade.php`.
- Reutilização de componentes/parciais em formulários e mensagens.
- Extra: cadastro, login e logout de usuário.
- Extra: cada usuário visualiza apenas as próprias categorias, projetos e etapas.
- Extra: configuração de ano letivo por semestre, trimestre ou bimestre.
- Extra: filtro inicial de projetos por ano letivo e período atual do usuário.
- Extra: upload de imagem nos projetos usando o disco `public`.
- Extra: etapas de desenvolvimento com atualizações, problemáticas e soluções/métodos de gestão.

## Modelagem do banco

### users

| Campo | Tipo | Descrição |
| --- | --- | --- |
| id | integer | Chave primária |
| name | string | Nome do usuário |
| email | string | E-mail usado no login |
| password | string | Senha criptografada |
| tipo_periodo | enum | Define se o usuário organiza o ano por semestre, trimestre ou bimestre |
| created_at / updated_at | timestamps | Controle de cadastro |

### categorias

| Campo | Tipo | Descrição |
| --- | --- | --- |
| id | integer | Chave primária |
| user_id | foreign key | Dono da categoria |
| nome | string | Nome da categoria |
| descricao | text | Descrição opcional |
| cor | string | Cor usada na interface |
| ativo | boolean | Define se a categoria pode ser usada |
| created_at / updated_at | timestamps | Controle de cadastro |

### projetos

| Campo | Tipo | Descrição |
| --- | --- | --- |
| id | integer | Chave primária |
| user_id | foreign key | Dono do projeto |
| categoria_id | foreign key | Relaciona com `categorias.id` |
| titulo | string | Título do projeto |
| responsavel | string | Nome do responsável |
| resumo | text | Resumo do projeto |
| status | enum | Planejamento, Em andamento, Concluído ou Pausado |
| ano_letivo | integer | Ano letivo do projeto |
| periodo_letivo | integer | Semestre, trimestre ou bimestre conforme configuração do usuário |
| data_inicio | date | Data inicial opcional |
| data_entrega | date | Prazo opcional |
| imagem | string | Caminho da imagem enviada |
| created_at / updated_at | timestamps | Controle de cadastro |

### etapa_projetos

| Campo | Tipo | Descrição |
| --- | --- | --- |
| id | integer | Chave primária |
| projeto_id | foreign key | Relaciona com `projetos.id` |
| titulo | string | Nome da etapa do desenvolvimento |
| data_registro | date | Data da atualização |
| status | enum | Planejada, Em execução, Concluída ou Bloqueada |
| descricao | text | Atualização realizada |
| problematica | text | Problema, dificuldade ou risco encontrado |
| metodo_gestao | text | Solução, decisão ou técnica usada para gerir a etapa |
| ordem | integer | Ordem de exibição na linha do tempo |
| created_at / updated_at | timestamps | Controle de cadastro |

## Como rodar

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

Depois acesse `http://127.0.0.1:8000`.

Links úteis durante a execução:

```text
Sistema Laravel: http://127.0.0.1:8000
phpMyAdmin / MySQL XAMPP: http://localhost/phpmyadmin
Banco do projeto: http://localhost/phpmyadmin/index.php?route=/database/structure&db=pw2_projetos
```

Usuário de demonstração criado pelo seeder:

```text
E-mail: admin@pw2.local
Senha: password
```

## Banco MySQL no XAMPP

O projeto está configurado para usar o MySQL do XAMPP. Antes de rodar as migrations, inicie **Apache** e **MySQL** no XAMPP e crie um banco chamado `pw2_projetos` pelo phpMyAdmin.

Link do phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Configuração esperada no `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pw2_projetos
DB_USERNAME=root
DB_PASSWORD=
```

Depois rode:

```bash
php artisan migrate:fresh --seed
```

## Roteiro para vídeo de até 3 minutos

1. Apresentar a ideia: sistema para organizar projetos acadêmicos da disciplina PW2.
2. Mostrar o cadastro/login e explicar que cada usuário escolhe a organização do ano letivo e enxerga apenas os próprios dados.
3. Mostrar o dashboard com totais, projetos recentes e categorias.
4. Explicar a modelagem: `users`, `categorias`, `projetos` e `etapa_projetos`.
5. Demonstrar CRUD de categorias: cadastrar, editar, visualizar e excluir quando não houver projeto vinculado.
6. Demonstrar CRUD de projetos: cadastrar projeto, escolher categoria, ano letivo, período, status, datas e imagem.
7. Abrir um projeto e mostrar as etapas de desenvolvimento, com atualização, problemática e método de gestão.
8. Mostrar os filtros iniciais por ano/período e depois os filtros extras por texto, categoria e status.
9. Finalizar apontando o uso de Laravel MVC, Blade, migrations, seeders, autenticação, layout reutilizável, upload de imagem e acompanhamento por etapas.

## Comandos de verificação

```bash
php artisan test
npm run build
```
