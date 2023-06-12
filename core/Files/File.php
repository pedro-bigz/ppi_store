<?php namespace Core\Files;

use SplFileInfo;
use Core\Exceptions\FileException;

class File extends SplFileInfo
{
    public function __construct(string $path)
    {
        parent::__construct($path);
    }

    public function validateFile()
    {
        return is_file($this->path);
    }

    public function throw404Error()
    {
        if ($this->validateFile($path)) {
            throw new FileNotFoundException($path);
        }
    }

    protected function getTargetFile(string $directory, string $name = null)
    {
        if (!is_dir($directory)) {
            if (@mkdir($directory, 0755, true) && !is_dir($directory) === false) {
                throw FileException::create('Unable to create the "%s" directory.', $directory);
            }
        } else if (!is_writable($directory)) {
            throw FileException::create('Unable to write in the "%s" directory.', $directory);
        }

        return new self($this->getFullPath($directory, $name));
    }

    public function getFullPath($directory, $name)
    {
        return $this->formatDirectoryName($directory).DIRECTORY_SEPARATOR.$this->obtainName($name);
    }

    public function formatDirectoryName($directory)
    {
        return rtrim($directory, '/\\');
    }
    
    public function obtainName($name)
    {
        return $name === null ?
            $this->getBasename() : $name;
    }

    public function getContent(): string
    {
        $content = file_get_contents($this->getPathname());

        if ($content === false) {
            throw FileException::create('Could not get the content of the file "%s".', $this->getPathname());
        }

        return $content;
    }
}