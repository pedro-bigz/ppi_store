<?php

interface FormRequestInterface
{
    function authorize();
    function rules();
    function getSanitized();
}