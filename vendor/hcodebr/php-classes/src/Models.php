<?php

namespace Hcode;

class Models {

    private $valores = [];

    public function __call($name, $arguments)
    {
        $methods = substr($name, 0, 3);
        $fieldName = substr($name, 3, strlen($name));

        //var_dump($methods, $fieldName);
        //exit;
        switch($methods){
            case "get":
                return (isset($this->valores[$fieldName]) ? $this->valores[$fieldName] : NULL);
            break;
            case "set":
                $this->valores[$fieldName] = $arguments[0];
            break;
        }
    }

    public function setDados(array $dados)
    {
        foreach ($dados as $key => $value) {
            $this->{"set".$key}($value);
        }
    }

    public function getValores()
    {
        return $this->valores; // Retornando um ARRAY de valores
    }

}