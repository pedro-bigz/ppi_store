<?php namespace App\Controllers;

use Core\Request\Request;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        dd($request->files()->moveAll(FILE_UPLOAD_PATH));
    }
}
