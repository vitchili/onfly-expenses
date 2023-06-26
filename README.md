

# API Onfly Expenses

Projeto de gerenciamento de despesas feito em PHP 8.2 e Laravel 10 com banco de dados MySql.

## Documentação API Onfly Expenses

### Efetuar Login

``
  POST http://localhost/onfly-expenses/public/api/login
``

#### Request body: JSON RAW

| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `email` | `string` | **Obrigatório**. E-mail do usuário |
| `password` | `string` | **Obrigatório**. Senha do usuário |

#### Responses:

```javascript
1) Exemplo Status 200 - Sucesso
  {
    "token": "19|N67pSqkAUdmIAyiTva7ARTZu0mZChrLLb8FcVPMm"
  }
```

#### Tratamento de erros:
```javascript
1) Exemplo Status 422 - Credenciais incorretas
  {
      "message": "As credenciais estão incorretas.",
      "errors": {
          "email": [
              "As credenciais estão incorretas."
          ]
      }
  }

  2) Exemplo Status 422 - Campos vazios ou inválidos.
  {
    "message": "O campo e-mail é obrigatório.",
    "errors": {
      "email": [
        "O campo e-mail é obrigatório."
      ]
    }
  }
```

### Criar novo usuário

``
  POST http://localhost/onfly-expenses/public/api/register
``

#### Request body: JSON RAW

| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `name` | `string` | **Obrigatório**. Nome do usuário |
| `email` | `string` | **Obrigatório**. E-mail do usuário |
| `password` | `string` | **Obrigatório**. Senha do usuário |

#### Responses:

```javascript
1) Exemplo Status 201 - Created
  {
    "token": "19|N67pSqkAUdmIAyiTva7ARTZu0mZChrLLb8FcVPMm"
  }
```

#### Tratamento de erros:
```javascript
1) Exemplo Status 422 - Credenciais já utilizadas
  {
      "message": "O campo e-mail já está sendo utilizado",
      "errors": {
          "email": [
              "O campo e-mail já está sendo utilizado."
          ]
      }
  }
  

2) Exemplo Status 422 - Campos vazios ou inválidos.
  {
    "message": "O campo e-mail é obrigatório.",
    "errors": {
      "email": [
        "O campo e-mail é obrigatório."
      ]
    }
  }
```

### Efetuar Logout

``
  POST http://localhost/onfly-expenses/public/api/logout
``

#### Authorization: Bearer [token_de_retorno_da_rota_/login]

#### Request body: [Vazio]

#### Responses:

```javascript
1) Exemplo Status 200 - Sucesso
  {
    "message": "Logout efetuado com sucesso."
  }

2) Exemplo Status 401 - Token inválido para logout
  {
    "message": "Usuário não autenticado."
  }
```

### Listar despesas

``
  GET http://localhost/onfly-expenses/public/api/expense/{id?}
``

#### Authorization: Bearer [token_de_retorno_da_rota_/login]

#### Request body: [Vazio]

A API retorna apenas despesas do usuário logado.
O campo 'id' é opcional para o path param. Se estiver setado, a API retornará a despesa pesquisada. Caso contrário, a API retornará todas despesas.

#### Responses:

```javascript
1) Exemplo Status 200 - Sucesso
  {
    "data": [
        {
            "id": 15,
            "description": "Despesa sócios empresa",
            "date": "2022-09-30 00:00:00",
            "user": {
                "id": 1,
                "name": "User test"
            },
            "value": "223.66"
        },
        {
            "id": 16,
            "description": "Teste despesa YZ2",
            "date": "2022-09-12 08:00:00",
            "user": {
                "id": 1,
                "name": "User test"
            },
            "value": "123.66"
        }
    ]
  }

  
2) Status 401 - Usuário não autenticado
  {
      "message": "Usuário não autenticado."
  }
```
#### Tratamento de erros:
```javascript
1) Pesquisar por despesa que não pertence ao usuário logado: 
{
    "request_status": "Erro ao realizar operação",
    "message": "Despesa não pertence a usuário logado."
}

2) Pesquisar por despesa inexistente:
{
    "request_status": "Erro ao realizar operação",
    "message": "Despesa não encontrada. ID: 99"
}
```

### Nova despesa

``
  POST http://localhost/onfly-expenses/public/api/expense
``

#### Authorization: Bearer [token_de_retorno_da_rota_/login]

#### Request body: JSON RAW

| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `description` | `string` | **Obrigatório**. Nome da despesa |
| `date` | `string` | **Obrigatório**. Data da despesa |
| `value` | `string` | **Obrigatório**. Valor da despesa |

#### Responses:

```javascript
1) Exemplo Status 201 - Created
  {
    "request_status": "Operação realizada com sucesso.",
    "id": 18
}


2) Status 401 - Usuário não autenticado
  {
      "message": "Usuário não autenticado."
  }
```
#### Tratamento de erros:
```javascript
1) Json inválido
{
    "message": "O json é inválido.",
    "errors": {
        "invalid_json": [
            "O json é inválido."
        ]
    }
}

2) Descrição, data e valores obrigatórios. 
Descrição deve ser string e com máximo 191 caracteres. Data deve ser válida e anterior ao dia de hoje. 
Valor deve ser numérico decimal válido.

{
    "message": "O campo \"descrição\" deve ter no máximo 191 caracteres. (and 2 more errors)",
    "errors": {
        "description": [
            "O campo \"descrição\" deve ter no máximo 191 caracteres."
        ],
        "date": [
            "O campo \"data\" deve ser anterior a hoje."
        ],
        "value": [
            "O campo \"valor\" deve ser decimal."
        ]
    }
}

```

### Editar despesa

``
  PUT http://localhost/onfly-expenses/public/api/expense/{id}
``

#### Authorization: Bearer [token_de_retorno_da_rota_/login]

#### Request body: JSON RAW

| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `description` | `string` | **Opcional**. Nome da despesa |
| `date` | `string` | **Opcional**. Data da despesa |
| `value` | `string` | **Opcional**. Valor da despesa |

Observação: O path param 'id' é obrigatório.

#### Responses:

```javascript
1) Exemplo Status 200 - Success
  {
    "request_status": "Operação realizada com sucesso.",
    "id": 18
}


2) Status 401 - Usuário não autenticado
  {
      "message": "Usuário não autenticado."
  }
```
#### Tratamento de erros:
```javascript
1) Json inválido
{
    "message": "O json é inválido.",
    "errors": {
        "invalid_json": [
            "O json é inválido."
        ]
    }
}

2) Descrição, data e valores obrigatórios. 
Descrição deve ser string e com máximo 191 caracteres. Data deve ser válida e anterior ao dia de hoje. 
Valor deve ser numérico decimal válido.

{
    "message": "O campo \"data\" deve ser anterior a hoje.",
    "errors": {
        "date": [
            "O campo \"data\" deve ser anterior a hoje."
        ]
    }
}

{
    "message": "O campo \"descrição\" deve ter no máximo 191 caracteres. (and 2 more errors)",
    "errors": {
        "description": [
            "O campo \"descrição\" deve ter no máximo 191 caracteres."
        ],
        "date": [
            "O campo \"data\" deve ser anterior a hoje."
        ],
        "value": [
            "O campo \"valor\" deve ser decimal."
        ]
    }
}

```

### Excluir despesa

``
  DELETE http://localhost/onfly-expenses/public/api/expense/{id}
``

#### Authorization: Bearer [token_de_retorno_da_rota_/login]

#### Request body: [Vazio]

Apenas despesas do próprio usuário logado podem ser excluídas. Id obrigatório

#### Responses:

```javascript
1) Exemplo Status 200 - Success
  {
    "request_status": "Despesa excluída com sucesso."
  }


2) Status 401 - Usuário não autenticado
  {
      "message": "Usuário não autenticado."
  }


3) 
```
#### Tratamento de erros:
```javascript
1) Despesa não encontrada
{
    "request_status": "Erro ao realizar operação",
    "message": "Despesa não encontrada. ID: 172"
}

2) Despesa não pertence ao usuário logado

{
    "request_status": "Erro ao realizar operação",
    "message": "Despesa não pertence a usuário logado."
}
```

#### Variáveis de ambiente
Serviço: Mailtrap

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=236d8ecb62a15f
MAIL_PASSWORD= *** (Consultar desenvolvedor ou criar nova conta e reconfigurar aqui)
MAIL_ENCRYPTION=tls

#### Observação
Database name: onflyexpenses




