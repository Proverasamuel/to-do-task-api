## 📘 README.md — To-Do List API (Laravel)

### 📝 Descrição

Esta é uma API RESTful desenvolvida com **Laravel** para gerenciamento de tarefas pessoais. Ela permite que usuários autenticados criem, visualizem, atualizem e excluam tarefas, bem como filtrem por status.

---

### 🚀 Requisitos

* PHP >= 8.1
* Composer
* MySQL
* Laravel 12


---

### ⚙️ Instalação

1. **Clone o projeto**

```bash
git clone https://github.com/seu-usuario/todo-api.git
cd todo-api
```

2. **Instale as dependências**

```bash
composer install
```

3. **Copie o arquivo `.env`**

```bash
cp .env.example .env
```

4. **Gere a chave da aplicação**

```bash
php artisan key:generate
```

5. **Configure o banco de dados no `.env`**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=teste1_laravel
DB_USERNAME=root
DB_PASSWORD=
```

6. **Execute as migrations**

```bash
php artisan migrate
```

7. **Popular com dados de teste**

```bash
php artisan db:seed
```

---

### 🔐 Autenticação

O projeto usa **Laravel Sanctum** para autenticação de APIs.

* Registre um novo usuário via `/api/register`
* Faça login via `/api/login`
* Use o token Bearer retornado para acessar as rotas protegidas

---

### 🧪 Executar Testes

```bash
php artisan test
```

Isso executa os testes automatizados definidos na pasta `tests/Feature`. Esses testes garantem que os principais comportamentos da API estejam funcionando corretamente:

#### ✅ Testes incluídos:

| Teste                                               | Descrição                                                                               |
| --------------------------------------------------- | --------------------------------------------------------------------------------------- |
| `user_can_create_a_task`                            | Verifica se o usuário autenticado consegue criar uma nova tarefa.                       |
| `user_can_list_his_tasks`                           | Garante que o usuário veja apenas suas tarefas.                                         |
| `user_can_update_task_status`                       | Testa se o usuário pode alterar o status da tarefa (ex: de `pending` para `completed`). |
| `user_can_delete_task`                              | Verifica se o usuário consegue excluir uma tarefa.                                      |
| `user_can_filter_tasks_by_status`                   | Permite ao usuário visualizar tarefas por status (`pending`, `completed`, etc).         |
| `validation_fails_when_creating_task_without_title` | Garante que tarefas sem título não sejam aceitas (validação).                           |
| `validation_fails_with_invalid_status`              | Impede a atualização de uma tarefa com status inválido.                                 |

Todos os testes usam autenticação com **Laravel Sanctum**, simulando um usuário real com `createToken()` e enviando um token válido nas requisições.

---

### 🧼 Documentação Swagger (OpenAPI)

A API é documentada com Swagger via **L5-Swagger**.

* Gere a documentação:

```bash
php artisan l5-swagger:generate
```

* Acesse no navegador:

```
http://localhost:8000/api/documentation
```

Inclui:

* Endpoints
* Parâmetros
* Exemplo de resposta
* Códigos HTTP

---

### 📬 Exemplos de Requisições

#### Criar Tarefa

```http
POST /api/tasks
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Estudar Laravel",
  "description": "Praticar Eloquent e APIs RESTful"
}
```

#### Filtrar por status

```http
GET /api/tasks/status/completed
Authorization: Bearer {token}
```

---

### 🧹 Boas Práticas Adotadas

* Arquitetura MVC
* Uso do Eloquent ORM
* Testes automatizados com PHPUnit
* Versionamento com Git

---

### 📂 Estrutura de Pastas (resumo)

```
app/
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   └── Resources/
├── Models/
├── Policies/
database/
├── factories/
├── migrations/
routes/
└── api.php
```

---

### 📄 Licença

Este projeto está sob a licença MIT.
