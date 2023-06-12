<?php namespace App\Controllers;

use Exception;
use Core\Request\Request;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        try {
            $path = current($request->files()->moveAll(FILE_UPLOAD_PATH))->getFilename();
            return response([
                'path' => $path,
                'message' => 'Upload do arquivo concluído com sucesso'
            ]);
        } catch (Exception $e) {
            return response(['message' => 'Erro ao fazer o upload do arquivo'], 500);
        }
    }
}
