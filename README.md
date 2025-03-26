# Teste Prático Back-end

Olá a todos, este é o resultado do meu teste prático de back-end. Desenvolvi o projeto mirando o **nível 2**, mas também implementei algumas funcionalidades do **nível 3**, como as roles (controle de permissões). Espero que este resultado esteja de acordo com as expectativas. Além disso, procurei seguir boas práticas de desenvolvimento e organizar o código de forma escalável e clara.

---

## Tecnologias Utilizadas

-   **Laravel 12**: Nova versão lançada recentemente, utilizada para o desenvolvimento da aplicação.
-   **MySQL**: Banco de dados utilizado para armazenar as informações do projeto.
-   **Docker**: Para rodar os mocks fornecidos para os gateways de pagamento.

---

## Como Rodar o Projeto

1. Clone o repositório (substitua pela URL do seu repositório quando disponível):

    ```bash
    git clone https://github.com/matcarmo3/teste_pratico_backend
    cd teste_pratico_backend

    ```

2. Instale as dependências do projeto:

    ```bash
    composer install

    ```

3. Configure o ambiente:

-   Duplique o arquivo .env.example e renomeie-o para .env.
-   Configure o banco de dados no .env e rode as migrações com as seed:
    ```bash
    php artisan migrate --seed
    ```

4. Inicie os mocks fornecidos para os gateways de pagamento:

    ```bash
    docker run -p 3001:3001 -p 3002:3002 matheusprotzen/gateways-mock

    ```

5. Por fim, inicie o servidor:
    ```bash
    php artisan serve
    ```

---

## Rotas Disponíveis

### Autenticação

-   **POST** `/login`: Autenticação do usuário.

    -   **Corpo da Requisição**:
        ```json
        {
            "email": "usuario@exemplo.com",
            "password": "senha"
        }
        ```

-   **POST** `/register`: Registro de um novo usuário.

    -   **Corpo da Requisição**:
        ```json
        {
            "name": "Usuario",
            "email": "usuario@exemplo.com",
            "password": "senha"
        }
        ```

-   **POST** `/logout`: Logout do usuário (requer autenticação).

### Gestão de Usuários (Role: `manager`)

-   **GET** `/users`: Listagem de usuários.

-   **GET** `/users/{id}`: Detalhes de um usuário.

-   **POST** `/users`: Criação de um novo usuário.

    -   **Corpo da Requisição**:
        ```json
        {
            "name": "Usuario",
            "email": "usuario@exemplo.com",
            "password": "senha",
            "role": "manager"
        }
        ```

-   **PUT** `/users/{id}`: Atualização de um usuário existente.

    -   **Corpo da Requisição**:
        ```json
        {
            "name": "Usuario",
            "email": "usuario@exemplo.com",
            "role": "manager"
        }
        ```

-   **DELETE** `/users/{id}`: Exclusão de um usuário.

### Produtos (Roles: `manager`, `finance`)

-   **GET** `/products`: Listagem de produtos.

-   **GET** `/products/{id}`: Detalhes de um produto.

-   **POST** `/products`: Criação de um novo produto.

    -   **Corpo da Requisição**:
        ```json
        {
            "name": "Produto Exemplo",
            "price": 20.99,
            "quantity": 10
        }
        ```

-   **PUT** `/products/{id}`: Atualização de um produto existente.

    -   **Corpo da Requisição**:
        ```json
        {
            "name": "Produto Exemplo",
            "price": 31.33,
            "quantity": 15
        }
        ```

-   **DELETE** `/products/{id}`: Exclusão de um produto.

### Gateways de Pagamento (Role: `admin`)

-   **GET** `/gateways`: Listagem de gateways de pagamento.

-   **GET** `/gateways/{id}`: Detalhes de um gateway.

-   **POST** `/gateways`: Criação de um novo gateway.

    -   **Corpo da Requisição**:
        ```json
        {
            "name": "Gateway Exemplo",
            "class_name": "GatewayExemplo",
            "priority": 1,
            "active": true
        }
        ```

-   **PUT** `/gateways/{id}`: Atualização de um gateway existente.

    -   **Corpo da Requisição**:
        ```json
        {
            "name": "Gateway Atualizado",
            "class_name": "GatewayExemplo",
            "priority": 2,
            "active": true
        }
        ```

-   **DELETE** `/gateways/{id}`: Exclusão de um gateway.

### Transações (Role: `finance`)

-   **GET** `/transactions`: Listagem de transações.

-   **GET** `/transactions/{id}`: Detalhes de uma transação.

-   **POST** `/transactions/{id}/refund`: Reembolso de uma transação.
    -   **Corpo da Requisição**:
        ```json
        {
            "reason": "Pedido cancelado"
        }
        ```

### Transações do Usuário

-   **GET** `/user/transactions`: Listagem das transações realizadas pelo usuário logado.

    -   **Corpo da Requisição**: Não é necessário corpo de requisição.

    **POST** `/transactions`: Criação de uma nova transação.

    -   **Corpo da Requisição**:
        ```json
        {
            "product_id": 1,
            "amount": 5,
            "card": "5569000000006063",
            "cvv": "100"
        }
        ```

---

## Arquivo de Importação para Postman

-   Disponibilizarei um arquivo configurado para facilitar o teste das rotas via Postman. Basta importá-lo e ajustar as variáveis de ambiente (como token de autenticação).

    ```bash
    https://www.google.com/

    ```

---

## Dificuldades Encontradas

-   Como fazia algum tempo que não desenvolvia APIs, precisei me atualizar sobre as novidades da nova versão do Laravel e a forma como ela trata algumas funcionalidades. Além disso, precisei organizar bem o controle de permissões (roles) e o fluxo das transações para que fossem claros e funcionais.
