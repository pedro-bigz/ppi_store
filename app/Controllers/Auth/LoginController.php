<?php namespace App\Controllers\Auth;

use App\Models\Anuncios;
use Core\Request\Request;
use App\Controllers\Controller;
use Core\Request\Factory\RequestFactory;

class LoginController extends Controller
{
    public function __construct(Request $request)
    {
        // var_dump($request);
    }
    public function index(Request $request)
    {
        echo '---';
        $request->ajax();
    }
}
