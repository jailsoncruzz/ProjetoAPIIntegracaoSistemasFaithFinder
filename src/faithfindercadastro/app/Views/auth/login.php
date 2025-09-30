<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Encontro de Fé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-5 text-center">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h1 class="h3 mb-4">FaithFinderCadastro</h1>
                        <p class="text-muted">Faça login para continuar</p>

                        <div id="g_id_signin" 
                             data-type="standard" 
                             data-size="large" 
                             data-theme="outline"
                             data-text="sign_in_with" 
                             data-shape="rectangular" 
                             data-logo_alignment="left">
                        </div>
                        <div id="error-message" class="text-danger mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://accounts.google.com/gsi/client" async defer></script>

    <script>
        
        function handleCredentialResponse(response) {
            const token = response.credential;
            const errorMessageDiv = document.getElementById('error-message');

            
            fetch('<?= site_url('auth/verify-google-token') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: 'credential=' + token
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    
                    window.location.href = data.redirect_url;
                } else {
                    errorMessageDiv.textContent = data.error || 'Ocorreu um erro no login.';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorMessageDiv.textContent = 'Falha na comunicação com o servidor.';
            });
        }

        
        window.onload = function () {
            google.accounts.id.initialize({
                client_id: '91311507423-aqi129op0r41muocn33itp6k52opc6sl.apps.googleusercontent.com',
                callback: handleCredentialResponse
            });
            google.accounts.id.renderButton(
                document.getElementById("g_id_signin"),
                { theme: "outline", size: "large" }
            );
        };
    </script>
</body>
</html>