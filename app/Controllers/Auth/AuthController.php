<?php namespace App\Controllers\Auth;

use App\Models\Anuncios;
use Core\Request\Request;
use App\Controllers\Controller;
use Core\Request\Factory\RequestFactory;
use App\Requests\AuthRequests\LoginRequest;

class AuthController extends Controller
{
    public function __construct(Request $request)
    {
        // var_dump($request);
    }

    public function index(Request $request)
    {
        // dd($request->ajax());
        view('login.index', [
            'title' => "Login",
        ]);
    }

    public function login(LoginRequest $request)
    {
        dd($request->getSanitized());
        return response(['message' => 'teste'], 403);
    }

    public function register(Request $request)
    {
        dd($request);
    }

    public function logout(Request $request)
    {
        dd($request);
    }
}
