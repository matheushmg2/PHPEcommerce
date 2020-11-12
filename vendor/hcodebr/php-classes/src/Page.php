<?php

namespace Hcode;

use Rain\Tpl;

class Page {

    private $tpl;
    private $Opcoes = [];
    private $Defaults = [
        "header" => true,
        "footer" => true,
        "dados"=> []
    ];


    public function __construct($opcao = array(), $tpl_dir = "/Views/")
    {
        $this->Opcoes = array_merge($this->Defaults, $opcao);


        $config = array(
            "tpl_dir"       =>  $_SERVER['DOCUMENT_ROOT'] . $tpl_dir,
            "cache_dir"     => $_SERVER['DOCUMENT_ROOT'] . "/Views-cache/",
            "debug"         => false // set to false to improve the speed
           );

        Tpl::configure( $config );

        $this->tpl = new Tpl;

        if($this->Opcoes['dados']) $this->setDados($this->Opcoes['dados']);

        if($this->Opcoes['header'] === true) $this->tpl->draw("header", false);

    }

    private function setDados($dados = array()){
        foreach ($dados as $key => $value) {
            $this->tpl->assign($key, $value);
        }
    }

    public function setTpl($name, $dados = array(), $retorneHTML = false)
    {
        $this->setDados($dados);

        return $this->tpl->draw($name, $retorneHTML);
    }




    public function __destruct()
    {
        if($this->Opcoes['footer'] === true) $this->tpl->draw("footer", false);
    }
}