<?php namespace App\Controllers;

use App\Models\Anuncios;
use App\Models\Enderecos;
use Core\Request\Request;
use App\Models\Categorias;
use App\Models\AnuncioFoto;
use Core\Listing\Paginator;
use App\Controllers\Controller;
use Core\Request\Factory\RequestFactory;
use App\Requests\AnunciosRequests\StoreAnuncioRequest;
use App\Requests\AnunciosRequests\UpdateAnuncioRequest;

class AnuncioController extends Controller
{
    public function __construct()
    {
        // var_dump($request);
    }

    public function index(Request $request)
    {
        if (! $request->ajax()) {
            return view('anuncios.index', [
                'title' => "Listagem de Anúncios",
            ]);
        }

        $data = Paginator::create(Anuncios::class)->processRequest(
            request: $request,
            searchIn: ['titulo', 'descricao'],
        )->toArray();

        foreach ($data['data'] as &$item) {
            $item['image'] = AnuncioFoto::select(
                where: "anuncio_id = :anuncio_id",
                bindings: ['anuncio_id' => $item['id']],
            )->first();
            $item['owner'] = $item['anunciante_id'] == 1;
        }

        return response(['data' => $data]);
    }

    public function show(Anuncios $anuncio)
    {
        return view('anuncios.show', [
            'title' => "Anuncio - {$anuncio->titulo}",
        ]);
    }

    public function create()
    {
        return view('anuncios.create', [
            'title' => "Anuncio",
            'categorias' => Categorias::select(),
            'enderecos' => Enderecos::select(),
            'bootstrap' => true,
        ]);
    }

    public function store(StoreAnuncioRequest $request)
    {
        try {
            $sanitized = $request->getSanitized();
            $anucio = Anuncios::create($sanitized);

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

    public function edit(Anuncios $anuncio)
    {
        return view('anuncios.edit', [
            'title' => "Editar Anuncio - {$anuncio->titulo}",
            'anuncio' => $anuncio,
            'categorias' => Categorias::select(),
            'enderecos' => Enderecos::select(),
            'bootstrap' => true,
        ]);
    }

    public function update(Anuncios $anuncio, UpdateAnuncioRequest $request)
    {
        try {
            $sanitized = $request->getSanitized();
            $anucio = $anuncio->update($sanitized);

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

    public function delete(Request $request, Anuncios $anuncio)
    {
        $anuncio->delete();
    }
}
