<?php namespace App\Controllers;

use App\Models\Anuncios;
use Core\Request\Request;
use App\Controllers\Controller;
use Core\Request\Factory\RequestFactory;

class AnuncioController extends Controller
{
    public function __construct()
    {
        // var_dump($request);
    }

    public function index(Request $request)
    {
        echo '---';
        // $request->ajax();
    }

    public function show(Anuncios $anuncio)
    {
        view('anuncios.index', [
            'title' => "Anuncio - {$anuncio->titulo}",
        ]);
    }
}
