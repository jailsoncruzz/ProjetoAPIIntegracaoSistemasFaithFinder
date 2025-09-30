# Requisitos da Aplicação 1: API de Gestão (FaithFinderCadastro)

Este documento detalha os requisitos funcionais e não-funcionais para a aplicação de backend, que serve como painel administrativo e provedor da API de dados.

### Requisitos Funcionais (RF)

* **RF1 - Autenticação de Administrador:** O sistema deve permitir que um usuário administrativo se autentique utilizando sua conta Google (OAuth 2.0).
* **RF2 - Controle de Acesso:** Todas as funcionalidades de gerenciamento (criação, edição, exclusão de locais) devem ser restritas apenas a usuários autenticados.
* **RF3 - Cadastro de Locais:** O usuário autenticado deve poder cadastrar novos locais (do tipo "igreja" ou "evento"), fornecendo informações detalhadas como nome, descrição, e endereço completo.
* **RF4 - Listagem de Locais:** O usuário autenticado deve poder visualizar uma lista de todos os locais que ele mesmo cadastrou. O sistema não deve exibir locais cadastrados por outros usuários.
* **RF5 - Edição de Locais:** O usuário autenticado deve poder editar as informações de um local que ele cadastrou previamente.
* **RF6 - Exclusão de Locais:** O usuário autenticado deve poder excluir (exclusão lógica/soft delete) um local que ele cadastrou.
* **RF7 - Geocodificação Automática:** Ao criar ou atualizar um local, o sistema deve se integrar a uma API externa (Nominatim) para converter o endereço em coordenadas geográficas (latitude e longitude) e salvá-las no banco de dados. 

* **RF8 - Endpoint de API para Listagem:** O sistema deve expor um endpoint público `GET /api/locais` para consulta de locais.
* **RF9 - Funcionalidade de Busca na API:** O endpoint de listagem deve suportar múltiplos filtros:
    * Busca por termo textual (`?q=...`) que pesquise em múltiplos campos de texto.
    * Busca por proximidade (`?lat=...&lon=...`) que retorne locais dentro de um raio definido.
* **RF10 - Endpoint de API para Detalhes:** O sistema deve expor um endpoint público `GET /api/locais/{id}` para retornar os dados de um local específico. 

* **RF11 - Tratamento de Erros da API:** A API deve retornar códigos de status HTTP apropriados e mensagens de erro claras em caso de falhas ou dados não encontrados. 

### Requisitos Não-Funcionais (RNF) 

* **RNF1 - Segurança:** O acesso ao painel de gestão deve ser protegido, e a verificação do token do Google deve ser feita no backend. A API pública é de somente leitura.

* **RNF2 - Usabilidade:** A interface do painel de gestão deve ser clara, intuitiva e responsiva, funcionando adequadamente em desktops e dispositivos móveis.

* **RNF3 - Desempenho:** As respostas da API para buscas textuais devem ter um tempo de resposta médio inferior a 500ms. Buscas por proximidade devem ter um tempo de resposta inferior a 1s.

* **RNF4 - Confiabilidade:** O sistema deve garantir a integridade dos dados através de chaves estrangeiras e transações de banco de dados, quando aplicável.

* **RNF5 - Documentação:** A API deve ser completamente documentada no arquivo `README.md`, detalhando cada endpoint, os parâmetros esperados e exemplos de resposta.