# Requisitos da Aplicação de Consumo: Portal de Busca (FaithFinderPortal)

Este documento detalha os requisitos funcionais e não-funcionais para a aplicação de frontend, que serve como a interface pública de busca para os usuários finais.

### Requisitos Funcionais (RF)

* **RF1 - Interface de Busca:** A aplicação deve apresentar uma interface limpa com um campo de busca proeminente como elemento principal.
* **RF2 - Busca por Texto Livre:** O usuário deve poder inserir um ou mais termos de texto para buscar locais. A aplicação deve consumir o endpoint `GET /api/locais?q=...` da Aplicação 1.
* **RF3 - Busca por Endereço:** O usuário deve poder digitar um endereço no campo de busca.
* **RF4 - Geocodificação de Endereço:** Ao detectar uma busca por endereço, a aplicação deve primeiro fazer uma chamada a uma API externa (Nominatim) para obter as coordenadas geográficas correspondentes.
* **RF5 - Busca por Proximidade:** Após obter as coordenadas, a aplicação deve consumir o endpoint `GET /api/locais?lat=...&lon=...` da Aplicação 1 para encontrar locais próximos.
* **RF6 - Busca por Localização Atual:** A aplicação deve oferecer um botão ou opção para que o usuário, se permitir, utilize a geolocalização do seu próprio navegador para realizar uma busca por proximidade.
* **RF7 - Exibição de Resultados:** Os resultados da busca devem ser exibidos de forma clara em uma lista ou em cards, mostrando informações relevantes como nome, tipo, endereço e a distância (no caso de busca por proximidade).
* **RF8 - Feedback Visual:** A aplicação deve exibir um indicador de carregamento (loading) enquanto as buscas na API estão em andamento.
* **RF9 - Personalização com Login:** O usuário pode opcionalmente fazer login com sua conta Google. Esta ação serve apenas para exibir uma mensagem de boas-vindas personalizada na interface; não há cadastro de usuários nesta aplicação.

### Requisitos Não-Funcionais (RNF)

* **RNF1 - Usabilidade:** A interface deve ser extremamente simples de usar, com um fluxo de busca direto e sem etapas desnecessárias.
* **RNF2 - Responsividade:** O layout da aplicação deve se adaptar e ser totalmente funcional em diferentes tamanhos de tela, de desktops a smartphones.
* **RNF3 - Desempenho:** A aplicação deve ter um tempo de carregamento inicial rápido. As interações do usuário (digitar, clicar em buscar) devem ter resposta imediata na interface.
* **RNF4 - Compatibilidade:** A aplicação deve ser compatível com as versões mais recentes dos principais navegadores (Google Chrome, Mozilla Firefox, Safari, Microsoft Edge).
* **RNF5 - Privacidade:** A aplicação só deve solicitar a localização do usuário após uma ação explícita (clique no botão de "usar minha localização") e deve usar a API padrão do navegador que exige a permissão do usuário.