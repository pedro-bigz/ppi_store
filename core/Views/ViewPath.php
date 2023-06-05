<?php namespace Core\Views;

final class ViewPath
{
    public static function resolver($path)
    {
        $breadcrumbs = explode('.', $path);
        $realpath = VIEW_PATH . DIRECTORY_SEPARATOR . implode('/', $breadcrumbs);

        return static::validate($realpath);
    }

    private static function validate(string $realpath, string|null $default = null)
    {
        if (!file_exists($realpath)) {
            return $default;
        }
        return $realpath;
    }
}