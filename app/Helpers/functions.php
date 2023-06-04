<?php

// if (!method_exists()) {
//     function cleanUrl()
//     {
//         //Eliminar as tags
//         $this->Url = strip_tags($this->Url);
//         //Eliminar espaços em branco
//         $this->Url = trim($this->Url);
//         //Eliminar a barra no final da URL
//         $this->Url = rtrim($this->Url, "/");

//         self::$Format = array();
//         self::$Format['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]?;:.,\\\'<>°ºª ';
//         self::$Format['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr--------------------------------';
//         $this->Url = strtr(utf8_decode($this->Url), utf8_decode(self::$Format['a']), self::$Format['b']);
//     }
// }