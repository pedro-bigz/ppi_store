<div class="container book-bg" style="height: 100%">
    <div class="page-header">
        <h3 class="page-title page-title-left bright-turquoise" style="text-align: center"><?= APP_NAME ?></h3>
    </div>
    <div class="card-form">
        <form class="form-global form-anuncio" action="<?= $anuncio->getResourceUrl() ?>" method="post" enctype="multipart/form-data">
            <div>
                <h2>Editar Anuncio <?= $anuncio->titulo ?></h2>
            </div>

            <input type="text" id="titulo" name="titulo" placeholder="Insira o título" value="<?= $anuncio->titulo ?>" />
            <input type="text" id="preco" name="preco" placeholder="Insira o preco" value="<?= $anuncio->preco ?>" />
            <select name="categoria" id="categoria">
                <?php foreach ($categorias->getAll() as $categoria): ?>
                    <option
                        value="<?= $categoria['id'] ?>"
                        <?= $categoria['id'] != $anuncio->categoria_id ?: 'selected' ?>
                    >
                        <?= $categoria['nome'] ?>
                    </option>
                <?php endforeach ?>
            </select>
            <select name="endereco" id="endereco">
                <?php foreach ($enderecos->getAll() as $endereco): ?>
                    <option
                        value="<?= $endereco['id'] ?>"
                        <?= $endereco['id'] != $anuncio->endereco_id ?: 'selected' ?>
                    >
                        <?= $endereco['logradouro'] ?>
                    </option>
                <?php endforeach ?>
            </select>
            <input
                type="hidden"
                name="file_bag"
                id="file_bag"
                value='<?= json_encode(array_map(fn($foto) => $foto['filename'], $fotos)) ?>'
            />
            <div class="preview-uploaded-container"></div>
            <div class="input-file-container"></div>
            <textarea id="descricao" name="descricao"><?= $anuncio->descricao ?></textarea>

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