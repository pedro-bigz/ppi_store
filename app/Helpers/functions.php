<?php

if (!function_exists('formataTelefone')) {
    function formataTelefone($numero){
        if (strlen($numero) == 10) {
            $novo = substr_replace($numero, '(', 0, 0);
            $novo = substr_replace($novo, '9', 3, 0);
            $novo = substr_replace($novo, ') ', 3, 0);
        } else {
            $novo = substr_replace($numero, '(', 0, 0);
            $novo = substr_replace($novo, ') ', 3, 0);
        }
        return $novo;
    }
}