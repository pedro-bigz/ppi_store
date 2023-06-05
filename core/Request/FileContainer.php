<?php namespace Core\Request;

class FileContainer extends ItemContainer
{
    private const KEYS = ['error', 'name', 'size', 'tmp_name', 'type'];

    public function __construct(array $items = [])
    {
        $this->init($items);
    }

    private function init($files)
    {
        foreach ($files as $key => $file) {
            parent::setItem($key, $this->convertFileInformation($file));
        }
    }

    protected function convertFileInformation()
    {
        $file = $this->items;

        if ($file instanceof RequestFile) {
            return $file;
        }

        $file = $this->adaptFilesArray($file);
        $keys = array_keys($file);

        sort($keys);

        if (static::KEYS == $keys) {
            $file = UPLOAD_ERR_NO_FILE != $file['error'] ?
                new RequestFile($file['tmp_name'], $file['name'], $file['type'], $file['error'], false) : null;
        } else if (array_keys($keys) === $keys) {
            $file = array_filter(
                array_map(function ($item) {
                    return $item instanceof RequestFile || is_array($item) ?
                        $this->convertFileInformation($item) : $item;
                }, $file)
            );        
        }

        return $file;
    }

    protected function adaptFilesArray(array $data)
    {
        unset($data['full_path']);

        $filename = $data['name'];
        $keys = array_keys($data);

        sort($keys);

        if (static::KEYS != $keys || !isset($filename) || !is_array($filename)) {
            return $data;
        }

        $files = $data;
        foreach (static::KEYS as $key) {
            unset($files[$key]);
        }

        foreach ($filename as $key => $name) {
            $files[$key] = $this->adaptFilesArray([
                'error' => $data['error'][$key],
                'name' => $name,
                'type' => $data['type'][$key],
                'tmp_name' => $data['tmp_name'][$key],
                'size' => $data['size'][$key],
            ]);
        }

        return $files;
    }
}