<div class="container book-bg" style="height: 100%">
    <div class="page-header">
        <h3 class="page-title page-title-left bright-turquoise"><?= APP_NAME ?></h3>
    </div>
    <div class="card-form">
        <form class="form-global form-anuncio" action="<?= url('/anuncios/store') ?>" method="post" enctype="multipart/form-data">
            <div>
                <h2>Cadastrar Anuncio</h2>
            </div>

            <input type="text" id="titulo" name="titulo" placeholder="Insira o título" />
            <input type="text" id="preco" name="preco" placeholder="Insira o preco" />
            <select name="categoria" id="categoria">
                <?php foreach ($categorias->getAll() as $categoria): ?>
                    <option value="<?= $categoria['id'] ?>"><?= $categoria['nome'] ?></option>
                <?php endforeach ?>
            </select>
            <select name="endereco" id="endereco">
                <?php foreach ($enderecos->getAll() as $endereco): ?>
                    <option value="<?= $endereco['id'] ?>"><?= $endereco['logradouro'] ?></option>
                <?php endforeach ?>
            </select>
            <input type="hidden" name="file_bag" id="file_bag" />
            <div class="preview-uploaded-container"></div>
            <div class="input-file-container"></div>
            <textarea id="descricao" name="descricao"></textarea>

            <div class="card-alert-success hidden">
                Sucesso
            </div>
            <div class="card-alert-error hidden">
                Erro
            </div>

            <button type="submit">Editar</button>
        </form>
    </div>
</div>