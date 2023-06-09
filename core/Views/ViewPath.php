<?php namespace Core\Views;

final class ViewPath
{
    public static function resolver($path)
    {
        $breadcrumbs = explode('.', $path);
        $realpath = VIEW_PATH . DIRECTORY_SEPARATOR . implode('/', $breadcrumbs);

        return static::validate($realpath.'.view.php');
    }

    private static function validate(string $realpath, string|null $default = null)
    {
        return file_exists($realpath) ? $realpath : $default;
    }
}