<?php

namespace Hcode\Models;
use Hcode\DB\Sql;
use Hcode\Models;

class Endereco extends Models {

    const SESSION_ERROR = "Error no Endereço";

    public static function getCEP($cep)
    {
        $cep = str_replace("-", "", $cep);

        // https://viacep.com.br/ws/35350000/json/

        $ci = curl_init(); // Iniando uma ponte para requerimento 

        curl_setopt($ci, CURLOPT_URL, "https://viacep.com.br/ws/$cep/json/");

        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);

        $dados = json_decode(curl_exec($ci), true);

        curl_close($ci); // Fechando essa ponte de requerimento 

        return $dados;
    }

    public function cargaDeCEP($cep)
    {
        $dados = Endereco::getCEP($cep);

        if(isset($dados['ibge']) && $dados['ibge']){

            //var_dump($dados);

            $this->setdesenderecos($dados['logradouro']);
            $this->setdescomplemento($dados['complemento']);
            $this->setdesdistritos($dados['bairro']); // District -> bairro
            $this->setdescidades($dados['localidade']);
            $this->setdesestados($dados['uf']);
            $this->setdespais('Brasil');
            $this->setdescodigopostal($dados['cep']); 
            // $this->setdescodigopostal($cep);
        }
    }

    public function salvarEnderecoComUsuario()
    {
        $sql = new Sql();

        $results = $sql->select("CALL sp_enderecos_salvo(:idenderecos, :tb_pessoas_idpessoas, :desenderecos, :descomplemento, :descidades, :desestados, :despais, :descodigopostal, :desdistritos)", [
            ":idenderecos" => $this->getidenderecos(),
            ":tb_pessoas_idpessoas" => $this->gettb_pessoas_idpessoas(),
            ":desenderecos" => $this->getdesenderecos(),
            ":descomplemento" => $this->getdescomplemento(),
            ":descidades" => $this->getdescidades(),
            ":desestados" => $this->getdesestados(),
            ":despais" => $this->getdespais(),
            ":descodigopostal" => $this->getdescodigopostal(),
            ":desdistritos" => $this->getdesdistritos()
        ]);

        if(count($results) > 0){
            $this->setDados($results[0]);
        }
    }

    public static function setMsgErros($msg)
    {
        $_SESSION[Endereco::SESSION_ERROR] = $msg;
    }

    public static function getMsgErros()
    {
        $msg = (isset($_SESSION[Endereco::SESSION_ERROR])) ? $_SESSION[Endereco::SESSION_ERROR] : "";

        Endereco::limparMsgErros();

        return $msg;
    }

    public static function limparMsgErros()
    {
        $_SESSION[Endereco::SESSION_ERROR] = NULL;
    }

}


