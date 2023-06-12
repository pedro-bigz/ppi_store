<?php namespace Core\Containers;

use Core\Request\RequestFile;
use Core\Exceptions\NoFileException;

class FileContainer extends ItemContainer
{
    private const FILE_PAYLOAD_KEY = 'file';
    private const KEYS = ['error', 'name', 'size', 'tmp_name', 'type'];

    public function __construct(array $items = [])
    {
        parent::__construct();
        $this->init($items);
    }

    private function init($files)
    {
        foreach ($files as $key => $file) {
            $this->setItem($key, $this->convertFileInformation($file[self::FILE_PAYLOAD_KEY]));
        }
    }

    protected function convertFileInformation($file)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw NoFileException::create(
                "Arquivo não recebido corretamente! (%s)", $file['name'] ?? ($file['full_path'] ?? FILE_PAYLOAD_KEY)
            );
        }

        return new RequestFile($file['tmp_name'], $file['name'], $file['type'], $file['error'], false);
    }

    public function moveAll($directory)
    {
        $uploaded = [];
        foreach ($this->items as $key => $file) {
            $uploaded[$key] = $file->moveWithUniqueId($directory);
        }
        return $uploaded;
    }
}