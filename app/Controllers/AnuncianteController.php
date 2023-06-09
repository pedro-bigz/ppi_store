<?php namespace App\Controllers;

use Core\Request\Request;

class AnuncianteController extends Controller
{
    public function login()
    {
        view('login.index', [
            'title' => "Login",
        ]);
    }

    public function register()
    {
        view('register.index', [
            'title' => "Cadastro",
        ]);
    }
}
