<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página não encontrada</title>

    <style>
        html, body {
            width: 100%;
            height: 100%;
            margin: 0 !important;
        }
        .container {
            padding: 1rem 3rem;
        }
        .trace {
            margin-top: 2rem;
        }
        .d-flex {
            display: flex;
            height: auto;
        }
        .trace-container {
            line-height: 1.25rem;
        }
        .file-container h4, .file-container p {
            margin: 0 !important;
        }
        .class-container h4, .class-container p {
            margin: 0 !important;
        }
        .type-container h4, .type-container p {
            margin: 0 !important;
        }
        .function-container h4, .function-container p {
            margin: 0 !important;
        }
        .h2, .h3 {
            font-family: sans-serif;
            margin: 0 !important;
        }
        .h2 {
            font-size: 2rem;
        }
        .h3 {
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="h2">Error</h2>
        <h3 class="h3"><?= $error->getMessage() ?></h3>
        <div class="trace">
            <?php
                foreach ($error->getTrace() as $trace) {
                    echo <<<HTML
                        <div class="trace-container">
                            <div class="d-flex file-container">
                                <h4>File:</h4>
                                <p>{$trace['file']}</p>
                            </div>
                            <div class="d-flex function-container">
                                <h4>Function:</h4>
                                <p>{$trace['function']}</p>
                            </div>
                            <div class="d-flex class-container">
                                <h4>Class:</h4>
                                <p>{$trace['class']}</p>
                            </div>
                            <div class="d-flex type-container">
                                <h4>Type:</h4>
                                <p>{$trace['type']}</p>
                            </div>
                            <hr />
                        </div>
                    HTML;
                }
            ?>
            </div>
    </div>
</body>
</html>