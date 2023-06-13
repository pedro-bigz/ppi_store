<div class="container book-bg" style="min-height: 100%">
    <div class="page-card-container">
        <div class="page-card-home">
            <div class="page-header-bar">
                <h3 class="page-title yellow"><?= APP_NAME ?></h3>
            </div>

            <div class="page-card-body" style="width: 100%">
                <div class="details-card">
                    <?php if (!empty($fotos)): ?>
                    <div class="galery-container">
                        <div class="galery-sidebar">
                            <?php foreach ($fotos as $foto): ?>
                                <div
                                    class="galery-item"
                                    style="background-image: url('<?= url("{$foto['folder']}/{$foto['filename']}") ?>');"
                                ></div>
                            <?php endforeach ?>
                        </div>
                        <div class="galery-content-preview">
                            <div
                                class="galery-item"
                                style="background-image: url('<?= url(isset($fotos[0]) ? "{$fotos[0]['folder']}/{$fotos[0]['filename']}" : "images/sem_foto.png") ?>');"
                            ></div>
                        </div>
                    </div>
                    <?php endif ?>
                    <div class="details-container">
                        <h2><?= $anuncio->titulo ?></h2>
                        <div class="details-price">
                            <div><?= number_format($anuncio->preco, 2, ',') ?></div>
                        </div>
                        <p><?= $anuncio->descricao ?></p>

                        <form class="form-interesses" action="<?= $anuncio->getResourceUrl() . '/purchase' ?>" method="post">
                            <h3>Interessou? Deixe uma mensagem para o anunciante</h3>
                            
                            <input type="text" name="nome" id="nome" placeholder="Informe seu nome" />
                            <input type="text" name="contato" id="contato" placeholder="Informe seu contato" />
                            <textarea name="mensagem" id="mensagem" placeholder="Interessou? Deixe sua mensagem para o anunciante"></textarea>
                            
                            <div class="card-alert-success hidden">
                                Sucesso
                            </div>
                            <div class="card-alert-error hidden">
                                Erro
                            </div>

                            <button type="submit" class="btn-send">Enviar</button>
                        </form>
                    </div>
                </div>
                <?php if (!empty($interesses)): ?>
                <div style="margin-top: 50px;">
                    <hr>
                    <h2>Interessados</h2>
                    <?php foreach ($interesses as $item): ?>
                    <div style="margin-top: 20px;">
                        <h3 style="margin: 5px 0;"><?= $item['nome'] ?></h3>
                        <p><b>Contato:</b> <?= formataTelefone($item['contato']) ?></p>
                        <p><b>Mensagem:</b> <?= $item['mensagem'] ?></p>
                    </div>
                    <hr>
                    <?php endforeach ?>
                </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>