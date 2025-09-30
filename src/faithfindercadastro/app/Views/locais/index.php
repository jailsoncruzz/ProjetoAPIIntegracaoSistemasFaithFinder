<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Locais Cadastrados</h2>
    <a href="<?= site_url('locais/new') ?>" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Novo Local</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Nome</th>
                <th>Cidade</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($locais as $local): ?>
                <tr>
                    <td><?= $local['id'] ?></td>
                    <td><span class="badge bg-secondary"><?= ucfirst($local['tipo']) ?></span></td>
                    <td><?= esc($local['nome']) ?></td>
                    <td><?= esc($local['cidade']) ?></td>
                    <td>
                        <a href="<?= site_url('locais/' . $local['id'] . '/edit') ?>" class="btn btn-sm btn-warning me-1" title="Editar"><i class="bi bi-pencil-fill"></i></a>
                        <form action="<?= site_url('locais/' . $local['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este local?');">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm btn-danger" title="Excluir"><i class="bi bi-trash-fill"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>