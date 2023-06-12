<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Anúncio' ?></title>

    <?php if (isset($share)): ?>
        <meta property="og:title" content="<?= $share['title'] ?>" />
        <meta property="og:url" content="<?= currentUrl() ?>" />
        <meta property="og:description" content="<?= $share['description'] ?>">
        <meta property="og:image" content="<?= $share['image'] ?>">
        <meta property="og:type" content="website" />
        <meta property="og:locale" content="America/Sao_Paulo" />
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:site" content="@nytimesbits" />
        <meta name="twitter:creator" content="PPI Anuncios" />
    <?php endif; ?>

    <link href="/css/styles.css" rel="stylesheet">
    <script src="/js/pwa.js"></script>

    <!-- </?php dd(empty($bootstrap)) ?> -->
    <?php if (!empty($bootstrap)): ?>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <?php endif ?>

</head>
<body>
    <?php if (isset($this->path)) include $this->path; ?>
    <div style="position: fixed; bottom: 10px; right: 10px;">
        <p>
            <a href="http://jigsaw.w3.org/css-validator/check/referer">
                <img
                    style="border:0;width:88px;height:31px"
                    src="http://jigsaw.w3.org/css-validator/images/vcss-blue"
                    alt="CSS válido!"
                />
            </a>
        </p>
    </div>
    <?php if (!empty($bootstrap)): ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php endif ?>
    <script type="module" src="/js/main.mjs"></script>
</body>
</html>