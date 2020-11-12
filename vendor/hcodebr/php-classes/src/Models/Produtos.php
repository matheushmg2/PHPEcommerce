<?php

namespace Hcode\Models;
use Hcode\DB\Sql;
use Hcode\Models;

class Produtos extends Models {


    public static function listaAll()
    {
        $sql = new Sql();
        return $sql->select("SELECT * FROM tb_produtos ORDER BY idprodutos DESC");
    }

    public static function listarProdutos($listas)
    {
        foreach ($listas as &$lista) {
            $Produto = new Produtos();
            $Produto->setDados($lista);
            $lista = $Produto->getValores();
        }

        return $listas;
    }

    public function SalvarProdutos()
    {
        $sql = new Sql();
        //$valores = ':' . implode(', :', array_keys($usuario)); 
        $results = $sql->select("CALL sp_produtos_salvo(:idprodutos, :desprodutos, :vlpreco, :vllargura, :vlaltura, :vlcomprimento, :vlpeso, :desurl)", array(
            ":idprodutos" => $this->getidprodutos(),
            ":desprodutos" => $this->getdesprodutos(),
            ":vlpreco" => $this->getvlpreco(),
            ":vllargura" => $this->getvllargura(),
            ":vlaltura" => $this->getvlaltura(),
            ":vlcomprimento" => $this->getvlcomprimento(),
            ":vlpeso" => $this->getvlpeso(),
            ":desurl" => $this->getdesurl()
        ));

        $this->setDados($results[0]);
    }

    public function get($idprodutos)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_produtos WHERE idprodutos = :idprodutos", array(
            ":idprodutos" => $idprodutos
        ));
        $this->setDados($results[0]);
    }

    public function delete()
    {
        $sql = new Sql();
        $results = $sql->select("DELETE FROM tb_produtos WHERE idprodutos = :idprodutos", array(
            ":idprodutos" => $this->getidprodutos()
        ));
    }

    public function getValores()
    {
        $this->inserirFotoProdutos();

        $valores = parent::getValores();

        return $valores;

    }

    private function inserirFotoProdutos()
    {
        if(file_exists(
            $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.
            "res".DIRECTORY_SEPARATOR.
            "Site".DIRECTORY_SEPARATOR.
            "img".DIRECTORY_SEPARATOR.
            "Produtos".DIRECTORY_SEPARATOR.
            $this->getidprodutos().".jpg")) {
            $url = "/res/Site/img/Produtos/".$this->getidprodutos().".jpg";
        } else {
            $url = "/res/Site/img/product.jpg";
        }
        return $this->setdesphoto($url);
    }

    public function setFotos($arquivoFoto)
    {
        $extension = explode('.', $arquivoFoto['name']);
        $extension = end($extension);
        switch ($extension){
            case "jpg":
                case "jpeg":
                    $image = imagecreatefromjpeg($arquivoFoto['tmp_name']);
                break;
            case "gif":
                $image = imagecreatefromgif($arquivoFoto['tmp_name']);
            break;
            case "png":
                $image = imagecreatefrompng($arquivoFoto['tmp_name']);
            break;
        }

        $destinho = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."res".DIRECTORY_SEPARATOR."Site".DIRECTORY_SEPARATOR."img".DIRECTORY_SEPARATOR."Produtos".DIRECTORY_SEPARATOR.$this->getidprodutos().".jpg";

        imagejpeg($image, $destinho);
        imagedestroy($image);
        $this->inserirFotoProdutos();
    }

    public function getFromUrl($desurl)
    {
        $sql = new Sql();
        $Resultado = $sql->select("SELECT * FROM tb_produtos WHERE desurl = :desurl LIMIT 1", array(
            ":desurl" => $desurl
        ));

        $this->setDados($Resultado[0]);
    }

    public function getCategoriasPertencentes()
    {
        $sql = new Sql();
        return $sql->select("SELECT * FROM tb_categorias categorias 
                    INNER JOIN tb_produtoscategorias proCategorias 
                    ON categorias.idcategorias = proCategorias.idcategorias 
                    WHERE proCategorias.idprodutos = :idprodutos", 
            array(
                ":idprodutos" => $this->getidprodutos()
            )
        );

    }

    public static function getPaginacaoDosProdutos($Paginas = 1, $QntItensProPg = 3)
    {

        $ComecarPg = ($Paginas - 1) * $QntItensProPg;

        $sql = new Sql();

        $results = $sql->select("SELECT SQL_CALC_FOUND_ROWS * 
            FROM tb_produtos ORDER BY idprodutos DESC
            LIMIT $ComecarPg, $QntItensProPg;"
        );

        $resultsTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

        return [
            "Dados" => $results,
            "Total" => (int)$resultsTotal[0]['nrtotal'],
            "PaginasGeradas" => ceil($resultsTotal[0]['nrtotal'] / $QntItensProPg)
        ];
    }

    public static function getPaginacaoDePesquisaAoProdutos($pesquisa, $Paginas = 1, $QntItensProPg = 3)
    {

        $ComecarPg = ($Paginas - 1) * $QntItensProPg;

        $sql = new Sql();

        $results = $sql->select("SELECT SQL_CALC_FOUND_ROWS * 
            FROM tb_produtos 
            WHERE desprodutos LIKE :pesquisa
            ORDER BY idprodutos DESC
            LIMIT $ComecarPg, $QntItensProPg;", [
                ":pesquisa" => '%'.$pesquisa.'%'
            ]
        );

        $resultsTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

        return [
            "Dados" => $results,
            "Total" => (int)$resultsTotal[0]['nrtotal'],
            "PaginasGeradas" => ceil($resultsTotal[0]['nrtotal'] / $QntItensProPg)
        ];
    }
}