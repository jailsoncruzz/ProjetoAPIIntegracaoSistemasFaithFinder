<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resultados dos Testes Simples</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Resultados dos Testes</h1>
        <ul class="list-group">
            <?php foreach ($results as $result): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <?= esc($result['name']) ?>
                        <?php if (!empty($result['message'])): ?>
                            <small class="d-block text-muted"><?= esc($result['message']) ?></small>
                        <?php endif; ?>
                    </div>
                    <span class="badge <?= esc($result['class']) ?> rounded-pill"><?= esc($result['status']) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>