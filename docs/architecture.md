# Arquitetura da Solução FaithFinder

Este documento descreve a arquitetura da solução "FaithFinder", que é composta por duas aplicações principais, independetes, que trabalham em conjunto para fornecer a funcionalidade completa do sistema.

## Componentes Principais

### 1. Aplicação 1: API de Gestão (`FaithFinderCadastro`)

Esta é a aplicação principal que cadastra os locais e fornece a API de consulta, o coração do sistema.

* **Framework:** CodeIgniter 4
* **Linguagem:** PHP 7.4.33
* **Banco de Dados:** MySQL
* **Padrão de Arquitetura:** MVC (Model-View-Controller)

#### Responsabilidades:
* **Painel de Administração:** Fornece uma interface web (Views) para que administradores possam realizar operações de CRUD (Criar, Ler, Atualizar, Deletar) nos dados de locais (igrejas e eventos).
* **Autenticação:** O acesso ao painel é protegido por um sistema de login que se integra com a API do Google (Google Identity Services) para autenticação OAuth 2.0.
* **Lógica de Negócio:** Contém toda a lógica para validação de dados, gestão de usuários e a integração com serviços externos.
* **Integração Externa (Geocodificação):** Ao salvar um endereço, o backend faz uma chamada server-to-server para a API pública **Nominatim (OpenStreetMap)** para converter o endereço textual em coordenadas geográficas (`latitude`, `longitude`), que são armazenadas no banco de dados.
* **Exposição da API RESTful:** Disponibiliza um conjunto de endpoints públicos (ex: `/api/locais`) para que aplicações cliente possam consumir os dados de forma padronizada (JSON).

### 2. Aplicação 2: Portal de Busca (`FaithFinderPortal`)

Esta é a que consome a API da FaithFinderPortal

* **Framework:** CodeIgniter 4 (atuando principalmente para servir a página inicial).
* **Linguagem:** PHP 7.4.33
* **Tecnologias Chave:** JavaScript puro (ES6+) com `fetch` API, Bootstrap 5.
* **Padrão de Arquitetura:** Single-Page Application (SPA) *like*.

#### Responsabilidades:
* **Interface do Usuário:** Apresenta uma página de busca simples e intuitiva para o usuário final.
* **Consumo da API Interna:** Toda a busca de dados é feita através de chamadas JavaScript (`fetch`) para os endpoints da API `FaithFinderCadastro`. A página não recarrega para exibir os resultados.
* **Lógica de Busca no Cliente:** O JavaScript é responsável por:
    * Capturar o termo de busca do usuário.
    * Realizar uma chamada para a API Nominatim caso o termo seja um endereço, para obter as coordenadas.
    * Construir a URL de requisição correta para a API interna (com parâmetros `q` ou `lat`/`lon`).
    * Renderizar os resultados recebidos em JSON no formato de cards HTML.
* **Personalização (Login):** Utiliza o Google Identity Services no lado do cliente para permitir que o usuário faça login, oferecendo uma experiência personalizada (ex: mensagem de "Olá, Nome"). A sessão é mantida no cliente, sem a necessidade de um banco de dados de usuários nesta aplicação.

## Fluxo de Dados de uma Busca por Proximidade

1.  Um usuário acessa a **FaithFinderPortal** e digita um endereço (ex: "Avenida da Universidade, Fortaleza") no campo de busca.
2.  O JavaScript do Portal captura o texto e faz uma requisição `fetch` para a API **Nominatim**.
3.  A Nominatim retorna as coordenadas (`-3.74, -38.53`).
4.  O JavaScript do Portal agora faz uma nova requisição `fetch` para a **API da FaithFinderCadastro**, passando as coordenadas como parâmetro: `GET /api/locais?lat=-3.74&lon=-38.53`.
5.  A **FaithFinderCadastro (API)** recebe a requisição. O `LocaisController` executa uma consulta SQL no banco de dados MySQL que utiliza a fórmula de Haversine para calcular a distância e filtrar todos os locais dentro de um raio pré-definido.
6.  A API retorna um array de locais em formato **JSON** para o Portal.
7.  O JavaScript do Portal recebe o JSON, faz um loop pelos resultados e cria dinamicamente os cards HTML para exibir os locais encontrados ao usuário.