<div class="container book-bg" style="height: 100%">
    <div class="page-header">
        <h2 class="page-title bright-turquoise"><?= APP_NAME ?></h2>
    </div>
    <div class="card-form">
        <form class="form-login" action="/auth/login" method="post">
            <div>
                <h2>Login</h2>
            </div>

            <input type="text" id="email" name="email" placeholder="Insira o email" />
            <input type="password" id="password" name="password" placeholder="Insira a senha" />

            <div class="card-alert-success hidden">
                Sucesso
            </div>
            <div class="card-alert-error hidden">
                Erro
            </div>

            <button type="submit">Entrar</button>
        </form>
    </div>
</div>