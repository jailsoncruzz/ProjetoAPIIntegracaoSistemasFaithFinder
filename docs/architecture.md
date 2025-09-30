# Arquitetura da Solução FaithFinder

Este documento descreve a arquitetura da solução "FaithFinder", que é composta por duas aplicações principais, independentes, que trabalham em conjunto com APIs externas para fornecer a funcionalidade completa do sistema.

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

Esta é a aplicação de frontend que consome a API da **`FaithFinderCadastro`**.

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

## Arquitetura de APIs Externas

O projeto integra dois serviços externos principais para prover suas funcionalidades.

### 1. API de Geolocalização: Nominatim (OpenStreetMap)

* **Propósito:** Converter endereços textuais em coordenadas geográficas (latitude e longitude), processo conhecido como Geocodificação.
* **Protocolo:** REST/HTTP (GET). 
* **Endpoint Principal:** `https://nominatim.openstreetmap.org/search`
* **Fluxo de Comunicação:**
    1.  O cliente (seja o backend do `FaithFinderCadastro` ou o frontend do `FaithFinderPortal`) monta uma string de endereço.
    2.  Uma requisição `GET` é enviada ao endpoint com o endereço como parâmetro de query (ex: `?q=Rua+X,Cidade+Y&format=json`).
    3.  A API da Nominatim retorna um array de resultados em formato JSON contendo, entre outros dados, os campos `lat` e `lon`.

### 2. API de Autenticação: Google Identity Services

* **Propósito:** Permitir que os usuários façam login nas aplicações de forma segura utilizando suas contas Google.
* **Protocolo:** OAuth 2.0 e OpenID Connect (OIDC).
* **Fluxo de Comunicação (Client-side):**
    1.  A aplicação frontend (`FaithFinderCadastro` ou `FaithFinderPortal`) carrega a biblioteca JavaScript do Google.
    2.  A biblioteca renderiza o botão "Sign in with Google".
    3.  O usuário clica no botão e completa a autenticação na janela pop-up do Google.
    4.  O servidor do Google retorna um **Token JWT (JSON Web Token)** para o JavaScript da aplicação.
    5.  No `FaithFinderCadastro`, este token é enviado ao backend, que o valida junto aos servidores do Google para autenticar o usuário e criar uma sessão segura.
    6.  No `FaithFinderPortal`, o token é decodificado no próprio frontend para obter informações básicas (nome, foto) e personalizar a interface.

## Diagrama de Arquitetura Completo

```mermaid
graph TD
    subgraph "Aplicações FaithFinder"
        A[Usuário Admin] -- Autentica via --> GIS[Google Identity Services];
        A -- Gerencia Locais --> App1[Painel de Gestão - FaithFinderCadastro];
        
        U[Usuário Final] -- Acessa --> App2[Portal de Busca - FaithFinderPortal];
    end

    subgraph "Serviços Externos"
        GIS[Google Identity Services];
        NOM[API Nominatim - OpenStreetMap];
    end

    subgraph "Backend & Dados"
        App1 -- Salva/Edita --> API[API RESTful - FaithFinderCadastro];
        API -- Geocodifica endereço --> NOM;
        API -- Salva/Consulta --> DB[Banco de Dados MySQL];
    end
    
    App2 -- Busca por endereço --> NOM;
    App2 -- Busca dados --> API;
end
