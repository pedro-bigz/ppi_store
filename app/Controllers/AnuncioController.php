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
        $data = [];

        if ($request->ajax()) {
            return response($data);
        }

        view('anuncios.index', [
            'title' => "Listagem de Anúncios",
        ]);
    }

    public function show(Anuncios $anuncio)
    {
        view('anuncios.show', [
            'title' => "Anuncio - {$anuncio->titulo}",
        ]);
    }
}
