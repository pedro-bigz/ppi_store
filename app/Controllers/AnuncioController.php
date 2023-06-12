<?php namespace App\Controllers;

use Core\Auth\Auth;
use App\Models\Anuncios;
use App\Models\Enderecos;
use Core\Request\Request;
use App\Models\Categorias;
use App\Models\Interesses;
use App\Models\AnuncioFoto;
use Core\Listing\Paginator;
use App\Controllers\Controller;
use Core\Request\Factory\RequestFactory;
use App\Requests\AnunciosRequests\StoreAnuncioRequest;
use App\Requests\AnunciosRequests\UpdateAnuncioRequest;
use App\Requests\AnunciosRequests\PurchaseAnuncioRequest;

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
            $item['owner'] = $item['anunciante_id'] == Auth::user()->getAuthIdentifier();
        }

        return response(['data' => $data]);
    }

    public function show(Anuncios $anuncio)
    {
        $fotos = AnuncioFoto::select(
            where: "anuncio_id = :anuncio_id",
            bindings: ['anuncio_id' => $anuncio->id],
        );

        $owner = $anuncio->anunciante_id == Auth::user()->getAuthIdentifier();

        if ($owner) {
            $interesses = Interesses::select(
                where: "anuncio_id = :anuncio_id",
                bindings: ['anuncio_id' => $anuncio->id],
            );
        } else {
            $interesses = Interesses::make();
        }

        return view('anuncios.show', [
            'title' => "Anuncio - {$anuncio->titulo}",
            'anuncio' => $anuncio,
            'fotos' => $fotos->getAll(),
            'interesses' => $interesses->getAll(),
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
        $sanitized = $request->getSanitized();
        try {
            $anuncio = Anuncios::create($sanitized);

            foreach ($sanitized['file_bag'] as $image) {
                $fotos = AnuncioFoto::create([
                    'filename' => $image,
                    'folder' => 'images',
                    'anuncio_id' => $anuncio->id,
                ]);
            }

            return response([
                'message' => 'Cadastro realizado com sucesso',
                'redirect' => url(),
            ]);
        } catch (NotFoundException $e) {
            return response(['message' => ''], 404);
        } catch (AuthenticationException $e) {
            return response(['message' => $e->getMessage()], 401);
        } catch (Throwable $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public function edit(Anuncios $anuncio)
    {
        $fotos = AnuncioFoto::select(
            where: "anuncio_id = :anuncio_id",
            bindings: ['anuncio_id' => $anuncio->id],
        );

        return view('anuncios.edit', [
            'title' => "Editar Anuncio - {$anuncio->titulo}",
            'anuncio' => $anuncio,
            'categorias' => Categorias::select(),
            'enderecos' => Enderecos::select(),
            'fotos' => $fotos->getAll(),
            'bootstrap' => true,
        ]);
    }

    public function fotos(Anuncios $anuncio)
    {
        try {
            $fotos = AnuncioFoto::select(
                where: "anuncio_id = :anuncio_id",
                bindings: ['anuncio_id' => $anuncio->id],
            );

            return response([ 'fotos' => $fotos->getAll() ]);
        } catch (NotFoundException $e) {
            return response([ 'fotos' => [] ]);
        } catch (Throwable $e) {
            return response([ 'message' => $e->getMessage() ], 500);
        }
    }

    public function update(Anuncios $anuncio, UpdateAnuncioRequest $request)
    {
        $sanitized = $request->getSanitized();
        try {
            $anucio = $anuncio->update($sanitized);

            // dd($sanitized);
            $fotos = AnuncioFoto::select(
                where: "anuncio_id = :anuncio_id",
                bindings: ['anuncio_id' => $anuncio->id],
            );

            foreach ($fotos->getAll() as $foto) {
                AnuncioFoto::make()->setAttributes($foto)->delete();
            }

            foreach ($sanitized['file_bag'] as $image) {
                $fotos = AnuncioFoto::create([
                    'filename' => $image,
                    'folder' => 'images',
                    'anuncio_id' => $anuncio->id,
                ]);
            }

            return response([
                'message' => 'Cadastro realizado com sucesso!',
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
    
    public function purchase(PurchaseAnuncioRequest $request, Anuncios $anuncio)
    {
        $sanitized = $request->getSanitized();
        try {
            Interesses::create([
                'nome' => $sanitized['nome'],
                'contato' => $sanitized['contato'],
                'mensagem' => $sanitized['mensagem'],
                'anuncio_id' => $anuncio->id,
            ]);

            return response([
                'message' => 'Mensagem enviada com sucesso!',
            ]);
        } catch (Throwable $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public function delete(Request $request, Anuncios $anuncio)
    {
        try {
            $fotos = AnuncioFoto::select(
                where: "anuncio_id = :anuncio_id",
                bindings: ['anuncio_id' => $anuncio->id],
            );

            foreach ($fotos->getAll() as $foto) {
                AnuncioFoto::make()->setAttributes($foto)->delete();
            }

            $anuncio->delete();

            return response([
                'message' => 'Deletado com sucesso',
            ]);
        } catch (Throwable $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }
}
