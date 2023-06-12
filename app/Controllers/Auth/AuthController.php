<?php namespace App\Controllers\Auth;

use Throwable;
use App\Models\Anuncios;
use Core\DateTime\Moment;
use Core\Request\Request;
use App\Models\Anunciantes;
use App\Controllers\Controller;
use Core\Exceptions\NotFoundException;
use Core\Request\Factory\RequestFactory;
use App\Exceptions\AuthenticationException;
use App\Requests\AuthRequests\LoginRequest;
use App\Requests\AuthRequests\RegisterRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $sanitized = $request->getSanitized();

        try {
            $anuciante = Anunciantes::select(
                columns: ['id', 'password', 'email'],
                where: "email = :email",
                bindings: ['email' => $sanitized['email']],
                first: true,
            );
            if ($anuciante->isEmpty()) {
                throw NotFoundException::create(
                    "Item não encontrado! (SQL: {$this->bindToLog($query, $bindings)})"
                );
            }
            if (!password_verify($sanitized['password'], $anuciante->password)) {
                throw AuthenticationException::create();
            }

            $_SESSION['auth'] = base64_encode(json_encode([
                'id' => $anuciante->id, 
                'email' => $anuciante->email,
                'start' => Moment::now()->format(Moment::FORMAT),
            ]));

            return response([
                'message' => 'Login realizado com sucesso',
                'redirect' => url(),
            ]);
        } catch (NotFoundException $e) {
            return response(['message' => 'Usuário não cadastrado'], 404);
        } catch (AuthenticationException $e) {
            return response(['message' => $e->getMessage()], 401);
        } catch (Throwable $e) {
            return response(['message' => 'A autenticação falhou'], 500);
        }
    }

    public function register(RegisterRequest $request)
    {
        $sanitized = $request->getSanitized();
        try {
            $register = Anunciantes::select(
                columns: ['count(1) as exist'],
                where: "email = :email",
                bindings: ['email' => $sanitized['email']],
                first: true,
            );
            if ($register->get('exist')) {
                throw AuthenticationException::create(AuthenticationException::EMAIL_EXISTS);
            }

            $anuciante = Anunciantes::create($sanitized);

            return response([
                'message' => 'Cadastro realizado com sucesso',
                'redirect' => url(),
            ]);
        } catch (NotFoundException $e) {
            return response(['message' => 'A autenticação falhou'], 404);
        } catch (AuthenticationException $e) {
            return response(['message' => $e->getMessage()], 401);
        } catch (Throwable $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        unset($_SESSION['auth']);
        redirect(url('/login'));
    }
}
