<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
    <h2><?= isset($local) ? 'Editar Local' : 'Novo Local' ?></h2>

    <form action="<?= isset($local) ? site_url('locais/' . $local['id']) : site_url('locais') ?>" method="post">
        
        <?php if (isset($local)): ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= old('nome', $local['nome'] ?? '') ?>" required>
            </div>
            <div class="col-md-3 mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select class="form-select" id="tipo" name="tipo">
                    <option value="igreja" <?= (old('tipo', $local['tipo'] ?? '') === 'igreja') ? 'selected' : '' ?>>Igreja</option>
                    <option value="evento" <?= (old('tipo', $local['tipo'] ?? '') === 'evento') ? 'selected' : '' ?>>Evento</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label for="data_referencia" class="form-label">Data (para eventos)</label>
                <input type="datetime-local" class="form-control" id="data_referencia" name="data_referencia" value="<?= old('data_referencia', $local['data_referencia'] ?? '') ?>">
            </div>
        </div>
        
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao" rows="3"><?= old('descricao', $local['descricao'] ?? '') ?></textarea>
        </div>

        <h4 class="mt-4">Endereço</h4>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label for="cep" class="form-label">CEP</label>
                <input type="text" class="form-control" id="cep" name="cep" value="<?= old('cep', $local['cep'] ?? '') ?>" required>
            </div>
            <div class="col-md-7 mb-3">
                <label for="rua" class="form-label">Rua</label>
                <input type="text" class="form-control" id="rua" name="rua" value="<?= old('rua', $local['rua'] ?? '') ?>" required>
            </div>
            <div class="col-md-2 mb-3">
                <label for="numero" class="form-label">Número</label>
                <input type="text" class="form-control" id="numero" name="numero" value="<?= old('numero', $local['numero'] ?? '') ?>" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="bairro" class="form-label">Bairro</label>
                <input type="text" class="form-control" id="bairro" name="bairro" value="<?= old('bairro', $local['bairro'] ?? '') ?>" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="cidade" class="form-label">Cidade</label>
                <input type="text" class="form-control" id="cidade" name="cidade" value="<?= old('cidade', $local['cidade'] ?? '') ?>" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="estado" class="form-label">Estado (UF)</label>
                <input type="text" class="form-control" id="estado" name="estado" maxlength="2" value="<?= old('estado', $local['estado'] ?? '') ?>" required>
            </div>
        </div>
         <div class="mb-3">
            <label for="complemento" class="form-label">Complemento</label>
            <input type="text" class="form-control" id="complemento" name="complemento" value="<?= old('complemento', $local['complemento'] ?? '') ?>">
        </div>
        
        <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i> Salvar</button>
        <a href="<?= site_url('/locais') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
<?= $this->endSection() ?>