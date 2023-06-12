<?php namespace Core\Request;

use SplFileInfo;
use Core\Files\File;
use Core\Exceptions\FileException;
use Core\Exceptions\NoFileException;
use Core\Exceptions\IniSizeFileException;
use Core\Exceptions\PartialFileException;
use Core\Exceptions\FormSizeFileException;
use Core\Exceptions\NoTmpDirFileException;
use Core\Exceptions\ExtensionFileException;
use Core\Exceptions\CannotWriteFileException;

final class RequestFile extends File
{
    public function __construct(string $path, string $name, string $mimeType = null, int $error = null)
    {
        $this->path = $path;
        $this->name = $name;
        $this->mimeType = $mimeType ?: 'application/octet-stream';
        $this->error = $error;

        parent::__construct($path);
    }

    public function validate()
    {
        return UPLOAD_ERR_OK === $this->error && is_uploaded_file($this->getPathname());
    }

    public function getMimeType()
    {
        return $this->mimeType;
    }

    public function throwException()
    {
        $errors = [
            UPLOAD_ERR_INI_SIZE => function() {
                throw IniSizeFileException::create(
                    'The file "%s" exceeds your upload_max_filesize ini directive (limit is %d KiB).', $this->name, $maxFilesize
                );
            },
            UPLOAD_ERR_FORM_SIZE => function() {
                throw FormSizeFileException::create(
                    'The file "%s" exceeds the upload limit defined in your form.', $this->name
                );
            },
            UPLOAD_ERR_PARTIAL => function() {
                throw PartialFileException::create(
                    'The file "%s" was only partially uploaded.', $this->name
                );
            },
            UPLOAD_ERR_NO_FILE => function() {
                throw NoFileException::create(
                    'No file was uploaded.'
                );
            },
            UPLOAD_ERR_CANT_WRITE => function() {
                throw CannotWriteFileException::create(
                    'The file "%s" could not be written on disk.', $this->name
                );
            },
            UPLOAD_ERR_NO_TMP_DIR => function() {
                throw NoTmpDirFileException::create(
                    'File could not be uploaded: missing temporary directory.'
                );
            },
            UPLOAD_ERR_EXTENSION => function() {
                throw ExtensionFileException::create(
                    'File upload was stopped by a PHP extension.'
                );
            },
        ];

        if (! $thrower = $errors[$this->error]) {
            throw new FileException($this->getErrorMessage());
        }

        $thrower();
    }
    
    public function generateUniqueId($filename)
    {
        return strRandom(40).'.'.pathinfo($filename, PATHINFO_EXTENSION);
    }

    public function moveWithUniqueId($directory)
    {
        $this->move($directory, $this->generateUniqueId($this->name));
    }

    public function move(string $directory, string $name = null)
    {
        if (!$this->validate()) {
            $this->throwException(); 
        }

        $target = $this->getTargetFile($directory, $name);

        set_error_handler(function ($type, $msg) use (&$error) { $error = $msg; });
        try {
            $moved = move_uploaded_file($this->getPathname(), $target);
        } finally {
            restore_error_handler(); // Restaura a função anterior para gerenciamento de erro
        }
        if (!$moved) {
            throw new FileException(
                'Could not move the file "%s" to "%s" (%s).', $this->getPathname(), $target, strip_tags($error)
            );
        }

        @chmod($target, 0666 & ~umask());

        return $target;
    }

    // public function getMaxFilesize()
    // {
    //     $sizePostMax = $this->parseFilesize(ini_get('post_max_size'))
    //         ?: PHP_INT_MAX;
    //     $sizeUploadMax = $this->parseFilesize(ini_get('upload_max_filesize'))
    //         ?: PHP_INT_MAX;

    //     return min($sizePostMax, $sizeUploadMax);
    // }

    // private function parseFilesize(string $size)
    // {
    //     if ($size === '') {
    //         return 0;
    //     }

    //     $size = strtolower($size);

    //     $max = ltrim($size, '+');
    //     $base = 10;

    //     if (str_starts_with($max, '0x')) {
    //         $base = 16;
    //     } else if (str_starts_with($max, '0')) {
    //         $base = 8;
    //     }

    //     $max = intval($max, $base);

    //     switch (substr($size, -1)) {
    //         case 't': $max *= 1024;
    //         case 'g': $max *= 1024;
    //         case 'm': $max *= 1024;
    //         case 'k': $max *= 1024;
    //     }

    //     return $max;
    // }
}