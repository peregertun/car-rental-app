<?php

namespace CarRental\Core;

class Request
{
    private $path;
    private $method;
    private $form;

    public function __construct()
    {
        $pathArray = explode("?", $_SERVER["REQUEST_URI"]);
        $this->path = substr($pathArray[0], 1);
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->form = array_merge($_POST, $_GET);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getForm()
    {
        return $this->form;
    }
}
