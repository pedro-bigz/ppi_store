<div class="container book-bg" style="height: 100%">
    <div class="page-header">
        <h2 class="page-title bright-turquoise"><?= APP_NAME ?></h2>
    </div>
    <div class="card-form">
        <form class="form-global form-register" action="/auth/register" method="post">
            <div>
                <h2>Cadastro</h2>
            </div>

            <input type="text" id="first_name" name="first_name" placeholder="Insira o nome" />
            <input type="text" id="last_name" name="last_name" placeholder="Insira o sobrenome" />
            <input type="text" id="email" name="email" placeholder="Insira o email" />
            <input type="password" id="password" name="password" placeholder="Insira a senha" />
            <input type="text" id="fone" name="fone" placeholder="Insira o telefone" />

            <div class="card-alert-success hidden">
                Sucesso
            </div>
            <div class="card-alert-error hidden">
                Erro
            </div>

            <a
                style="margin-bottom: 20px"
                href="<?= url('/login') ?>"
            >
                Já possui cadastro? Entre
            </a>

            <button type="submit">Cadastrar</button>
        </form>
    </div>
</div>