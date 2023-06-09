<?php namespace Core\Request\Contracts;

interface FormRequestInterface
{
    public function authorize();
    public function rules();
    public function getSanitized();
}