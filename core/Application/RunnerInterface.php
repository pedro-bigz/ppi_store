<?php namespace Core\Application;

interface RunnerInterface
{
    public function run();

    public function getInstance(string $classname);

    public function initializeParams($params);

    public function getParams(string $classname);

    public function validateClass(string $classname);

    public function initializeClass(string $classname);

    public function config(): RunnerInterface;
}