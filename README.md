# Desafio PHP Júnior

Aplicação de gerenciamento de pacientes e unidades de saúde, desenvolvida com Laravel e MySQL.

O sistema disponibiliza uma API REST para cadastro, consulta, atualização, exclusão e pesquisa de pacientes, além de uma interface web em HTML, CSS e JavaScript puro.

## Tecnologias utilizadas

- PHP 8.2
- Laravel 12
- MySQL
- HTML, CSS e JavaScript
- Bootstrap 5
- PHPUnit (testes automatizados)

## Requisitos

- PHP 8.2 ou superior, compatível com Laravel 12
- Composer
- MySQL

## Instalação

**1. Clone o repositório:**

```bash
git clone https://github.com/DanilloFerreiraP/desafio.git
```

**2. Entre na pasta do projeto:**

```bash
cd desafio
```

**3. Instale as dependências:**

```bash
composer install
```

**4. Crie o arquivo de configuração a partir do exemplo:**

```bash
cp .env.example .env
```

**5. Gere a chave da aplicação:**

```bash
php artisan key:generate
```

**6. Crie um banco de dados vazio no MySQL**, por exemplo, `conectasus_desafio`.

**7. Configure a conexão no arquivo `.env`:**

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=conectasus_desafio
DB_USERNAME=root
DB_PASSWORD=
```

Ajuste o nome do banco, usuário e senha conforme seu ambiente.

**8. Execute as migrations e os seeders:**

```bash
php artisan migrate --seed
```

**9. Inicie o servidor:**

```bash
php artisan serve
```

**10. Acesse a interface web:**

http://127.0.0.1:8000/index.html

## Funcionalidades

- Cadastro de pacientes com nome, CPF, data de nascimento, telefone opcional e unidade de saúde.
- Listagem dos pacientes e respectivas unidades de saúde.
- Pesquisa parcial de pacientes pelo nome.
- Edição e exclusão de pacientes.
- Validação dos campos obrigatórios e CPF com 11 dígitos e valor único.
- Carregamento de unidades de saúde cadastradas pelo seeder.

A interface mantém a lista de pacientes em memória para realizar a pesquisa localmente. Após operações de cadastro, edição e exclusão, a lista é atualizada por meio da API.

## Endpoints da API

| Método | Endpoint | Descrição |
|---|---|---|
| GET | `/api/health-units` | Listar unidades de saúde |
| GET | `/api/patients` | Listar pacientes |
| GET | `/api/patients?search=Maria` | Pesquisar pacientes pelo nome |
| GET | `/api/patients/{id}` | Consultar um paciente |
| POST | `/api/patients` | Cadastrar paciente |
| PUT | `/api/patients/{id}` | Atualizar paciente |
| DELETE | `/api/patients/{id}` | Excluir paciente |

As operações de cadastro e atualização recebem dados em JSON.

**Exemplo de cadastro:**

```json
{
  "name": "Maria da Silva",
  "cpf": "12345678909",
  "birth_date": "1995-05-20",
  "phone": "88999999999",
  "health_unit_id": 1
}
```

O campo `health_unit_id` deve corresponder a uma unidade de saúde cadastrada no banco de dados.

## Testes automatizados

O projeto possui testes automatizados para a API de pacientes, utilizando PHPUnit.

Os testes verificam a listagem, o cadastro, a validação de CPF duplicado, a atualização e a exclusão de pacientes.

Para executar os testes, crie um banco MySQL chamado `conectasus_test` e configure o arquivo `.env.testing` com os dados de conexão desse banco.

Exemplo de configuração:

```dotenv
APP_ENV=testing

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=conectasus_test
DB_USERNAME=root
DB_PASSWORD=
```

O arquivo `phpunit.xml` também deve apontar para o banco `conectasus_test`.

Após configurar o ambiente, confirme se a conexão está apontando para o banco de testes:

```bash
php artisan tinker --env=testing
```

Dentro do Tinker, execute:

```php
DB::selectOne('SELECT DATABASE() AS banco');
```

O resultado deve indicar `conectasus_test`. Digite `exit` para sair do Tinker.

Execute as migrations no banco de testes:

```bash
php artisan migrate --env=testing
```

Para verificar se as migrations foram executadas:

```bash
php artisan migrate:status --env=testing
```

Execute os testes automatizados:

```bash
php artisan test --filter=PatientApiTest
```

Os testes utilizam `RefreshDatabase` para manter os dados isolados. Não utilize o banco principal da aplicação para executá-los.

## Consultas SQL

O arquivo `sql/consultas.sql` contém as três consultas solicitadas no desafio:

1. Listar os pacientes e os nomes das respectivas unidades de saúde utilizando `JOIN`.
2. Contar os pacientes por unidade, incluindo unidades sem pacientes, utilizando `LEFT JOIN` e `GROUP BY`.
3. Pesquisar pacientes por parte do nome utilizando `LIKE`.

As consultas podem ser executadas diretamente no MySQL ou no phpMyAdmin.

## Organização do projeto

- `app/Http/Controllers/Api/` — Controllers da API.
- `app/Http/Requests/` — Validação dos dados de pacientes.
- `app/Models/` — Models e relacionamentos.
- `database/migrations/` — Estrutura das tabelas.
- `database/seeders/` — Dados iniciais das unidades de saúde.
- `routes/api.php` — Rotas da API REST.
- `public/index.html` — Interface web.
- `public/js/patients.js` — Consumo da API e interações.
- `public/css/patients.css` — Estilos da interface.
- `sql/consultas.sql` — Consultas SQL obrigatórias.