<div class="container book-bg" style="min-height: 100%">
    <div class="page-card-container">
        <div class="page-card-home">
            <div class="page-header-bar">
                <h3 class="page-title yellow"><?= APP_NAME ?></h3>
                <div>
                    <a class="advertising-create" href="<?= url('anuncios/create') ?>">Cadatrar Anuncio</a>
                </div>
            </div>

            <form class="search-bar" action="<?= url() ?>" method="get">
                <input type="text" name="search" id="search" placeholder="Pesquisar" />
                <select name="columns" id="columns">
                    <option value="">*</option>
                    <option value="titulo">Título</option>
                    <option value="descricao">Descrição</option>
                </select>
                <button>
                    <?php include __DIR__."/../icons/search.view.php" ?>
                </button>
            </form>

            <div class="page-card-body">
                <div class="advertising-card-container"></div>
            </div>
        </div>
    </div>
</div>