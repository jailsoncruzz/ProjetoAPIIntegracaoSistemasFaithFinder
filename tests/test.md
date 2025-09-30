# Guia de Testes da API com Postman

Este documento descreve os passos para executar uma série de testes funcionais nos endpoints principais da API, utilizando a coleção pré-configurada do Postman.

É necessario utilizar a aplicação para povar com dados do login via google.

## Configuração Inicial no Postman

1.  **Garanta que a API esteja rodando:** Seu servidor local (XAMPP ou `php spark serve`) para o projeto `faithfindercadastro` deve estar ativo.
2.  **Abra o Postman.**
3.  **Importe a Coleção:**
    * Vá em `File` > `Import...` (ou `Ctrl+O`).
    * Selecione o arquivo `collection.json` localizado na pasta `/postman` do projeto da API.
4.  **Verifique a Variável de Ambiente:**
    * A coleção importada, "FaithFinder API", usa uma variável chamada `{{baseUrl}}` para o endereço da API.
    * Clique no nome da coleção e vá para a aba "Variables".
    * Confirme que o valor da variável `baseUrl` está correto para o seu ambiente (ex: `http://localhost/faithfindercadastro/public`).

## Executando os Testes

Com a coleção importada e o servidor rodando, você pode executar as seguintes requisições para testar os endpoints.

### Teste 1: Listar Todos os Locais

* **Objetivo:** Verificar se a API retorna uma lista de todos os locais cadastrados no banco de dados.
* **Ação:** Na coleção "FaithFinder API", selecione a requisição **"Listar Todos os Locais"** e clique em **"Send"**.
* **Resultado Esperado:**
    * **Status:** `200 OK`.
    * **Corpo (Body):** Um array JSON com todos os locais existentes no seu banco de dados. Se o banco estiver vazio, ele retornará um array vazio (`[]`).

### Teste 2: Buscar Locais por Texto

* **Objetivo:** Verificar se a funcionalidade de busca textual (`?q=...`) está filtrando os resultados corretamente.
* **Ação:**
    1.  Selecione a requisição **"Buscar Locais por Texto"**.
    2.  Vá para a aba **"Params"**.
    3.  Altere o valor do parâmetro `q` para um termo que exista no seu banco de dados (ex: `Fortaleza` ou `Matriz`).
    4.  Clique