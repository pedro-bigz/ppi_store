<?php namespace App\Controllers;

use Core\Request\Request;

class AnuncianteController extends Controller
{
    public function login()
    {
        return view('login.index', [
            'title' => "Login",
        ]);
    }

    public function register()
    {
        return view('register.index', [
            'title' => "Cadastro",
        ]);
    }
}
