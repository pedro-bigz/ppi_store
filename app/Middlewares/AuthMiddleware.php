<?php namespace App\Middlewares;

use Core\Auth\Auth;
use Core\Request\Request;

class AuthMiddleware extends Middleware
{
    public function handler(Request $request)
    {
        if (empty($_SESSION['auth'])) {
            return redirect(url('/login'));
        }
        if (is_null(Auth::user()->getAuthIdentifier())) {
            return redirect(url('/login'));
        }
        return $this->next();
    }
}