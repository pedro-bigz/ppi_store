<?php

if (! function_exists('snakeCase')) {
    function snakeCase($input)
    {
        $pattern = '/(?<!^)[A-Z]/';
        $snakeCase = preg_replace($pattern, '_$0', $input);
    
        return strtolower($snakeCase);
    }
    
}

if (! function_exists('strRandom')) {
    function strRandom($length = 16)
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;
            $bytes = random_bytes($size);
            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }
}