<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FaithFinder Portal de Busca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">FaithFinder</a>
            <div id="auth-container">
                <div id="g_id_signin"></div>
            </div>
            <div id="user-profile" class="d-none align-items-center">
                <img id="user-pic" src="" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                <span id="user-name" class="navbar-text"></span>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <div class="text-center mb-5">
            <h1 class="display-5">Encontre Igrejas e Eventos</h1>
            <p class="lead text-muted">Busque por nome, descrição ou endereço para encontrar o que está perto de você.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form id="search-form" class="d-flex mb-3 shadow-sm">
                    <input id="search-input" type="search" class="form-control form-control-lg" placeholder="Digite nome ou endereço..." aria-label="Busca">
                    <button class="btn btn-primary btn-lg" type="submit"><i class="bi bi-search"></i></button>
                </form>
                <div class="text-center">
                    <button id="near-me-btn" class="btn btn-link">
                        <i class="bi bi-geo-alt-fill"></i> Usar minha localização atual
                    </button>
                </div>
            </div>
        </div>

        <div id="loading" class="text-center my-5 d-none">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Buscando...</span>
            </div>
        </div>

        <div id="results" class="row g-4 mt-4">
        </div>
    </main>

    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script>
        const API_BASE_URL = '<?= $apiUrl ?>';
        const GOOGLE_CLIENT_ID = '91311507423-aqi129op0r41muocn33itp6k52opc6sl.apps.googleusercontent.com';


        function handleCredentialResponse(response) {

            const base64Url = response.credential.split('.')[1];
            const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
            const jsonPayload = decodeURIComponent(atob(base64).split('').map(c => '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)).join(''));
            const userData = JSON.parse(jsonPayload);


            document.getElementById('auth-container').classList.add('d-none');
            document.getElementById('user-profile').classList.remove('d-none');
            document.getElementById('user-name').textContent = `Olá, ${userData.given_name}`;
            document.getElementById('user-pic').src = userData.picture;
        }

        window.onload = function() {
            google.accounts.id.initialize({
                client_id: GOOGLE_CLIENT_ID,
                callback: handleCredentialResponse
            });
            google.accounts.id.renderButton(
                document.getElementById("g_id_signin"), {
                    theme: "outline",
                    size: "large",
                    type: "standard",
                    shape: "pill"
                }
            );
        };


        const searchForm = document.getElementById('search-form');
        const searchInput = document.getElementById('search-input');
        const nearMeBtn = document.getElementById('near-me-btn');
        const resultsDiv = document.getElementById('results');
        const loadingDiv = document.getElementById('loading');

        searchForm.addEventListener('submit', (e) => {
            e.preventDefault();
            performSearch(searchInput.value);
        });

        nearMeBtn.addEventListener('click', () => {
            if (navigator.geolocation) {
                loadingDiv.classList.remove('d-none');
                resultsDiv.innerHTML = '';
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const coords = {
                            lat: position.coords.latitude,
                            lon: position.coords.longitude
                        };
                        performSearch(coords);
                    },
                    () => {
                        alert('Não foi possível obter sua localização. Verifique as permissões do navegador.');
                        loadingDiv.classList.add('d-none');
                    }
                );
            } else {
                alert('Geolocalização não é suportada por este navegador.');
            }
        });


        async function performSearch(query) {
            loadingDiv.classList.remove('d-none');
            resultsDiv.innerHTML = '';
            let finalApiUrl = '';

            if (typeof query === 'object' && query.lat && query.lon) {
                finalApiUrl = new URL('locais', API_BASE_URL);
                finalApiUrl.searchParams.append('lat', query.lat);
                finalApiUrl.searchParams.append('lon', query.lon);
            }
            
            else if (typeof query === 'string' && query.trim() !== '') {

                
                const isLikelyAddress = query.match(/\d/) && query.match(/[a-zA-Z]/); // ✅

                if (isLikelyAddress) {
                    try {
                        const nominatimUrl = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&limit=1`;
                        const geoResponse = await fetch(nominatimUrl);
                        const geoData = await geoResponse.json();

                        if (geoData.length > 0) {
                            finalApiUrl = new URL('locais', API_BASE_URL);
                            finalApiUrl.searchParams.append('lat', geoData[0].lat);
                            finalApiUrl.searchParams.append('lon', geoData[0].lon);
                        } else {
                           
                            finalApiUrl = new URL('locais', API_BASE_URL);
                            finalApiUrl.searchParams.append('q', query);
                        }
                    } catch (error) {
                        console.error('Erro na geocodificação:', error);
                        finalApiUrl = new URL('locais', API_BASE_URL);
                        finalApiUrl.searchParams.append('q', query);
                    }
                } else {
                    
                    finalApiUrl = new URL('locais', API_BASE_URL);
                    finalApiUrl.searchParams.append('q', query);
                }
            }

            
            if (!finalApiUrl) {
                resultsDiv.innerHTML = '<p class="text-center text-danger">Termo de busca inválido.</p>';
                loadingDiv.classList.add('d-none');
                return;
            }

            
            try {
                const apiResponse = await fetch(finalApiUrl.href);
                const locais = await apiResponse.json();
                renderResults(locais);
            } catch (error) {
                console.error('Erro ao buscar da API:', error);
                resultsDiv.innerHTML = '<p class="text-center text-danger">Não foi possível conectar à API de dados.</p>';
            } finally {
                loadingDiv.classList.add('d-none');
            }
        }

        function renderResults(locais) {
            if (locais.length === 0) {
                resultsDiv.innerHTML = '<p class="text-center text-muted">Nenhum resultado encontrado.</p>';
                return;
            }

            locais.forEach(local => {
                const card = `
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title">${local.nome}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">
                                    <span class="badge ${local.tipo === 'evento' ? 'bg-success' : 'bg-info'}">${local.tipo}</span>
                                </h6>
                            </div>
                            ${ local.distance ? `<span class="badge bg-primary rounded-pill">${parseFloat(local.distance).toFixed(1)} km</span>` : '' }
                        </div>
                        <p class="card-text">${local.descricao || ''}</p>
                        <hr>
                        <p class="card-text small text-muted">
                            <i class="bi bi-geo-alt-fill"></i> 
                            ${local.rua}, ${local.numero} - ${local.bairro}, ${local.cidade} - ${local.estado}
                        </p>
                        ${ local.tipo === 'evento' && local.data_referencia ? `<p class="card-text small text-success"><i class="bi bi-calendar-event-fill"></i> ${new Date(local.data_referencia).toLocaleString('pt-BR')}</p>` : '' }
                    </div>
                </div>
            </div>
            `;
                resultsDiv.innerHTML += card;
            });
        }
    </script>
</body>

</html>