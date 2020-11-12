<?php

namespace Hcode\Models;
use Hcode\DB\Sql;
use Hcode\Models;

class Categorias extends Models {


    public static function listaAll()
    {
        $sql = new Sql();
        return $sql->select("SELECT * FROM tb_categorias ORDER BY idcategorias DESC");
    }


    public function SalvarCategorias()
    {
        $sql = new Sql();
        //$valores = ':' . implode(', :', array_keys($usuario)); 
        $results = $sql->select("CALL sp_categorias_salvar(:idcategorias, :descategorias)", array(
            ":idcategorias" => $this->getidcategorias(),
            ":descategorias" => $this->getdescategorias()
        ));

        $this->setDados($results[0]);

        Categorias::updateListaCategoriasSite();
    }

    public function get($idcategorias)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_categorias WHERE idcategorias = :idcategorias", array(
            ":idcategorias" => $idcategorias
        ));
        $this->setDados($results[0]);
    }

    public function delete()
    {
        $sql = new Sql();
        $results = $sql->select("DELETE FROM tb_categorias WHERE idcategorias = :idcategorias", array(
            ":idcategorias" => $this->getidcategorias()
        ));

        Categorias::updateListaCategoriasSite();
    }

    public static function updateListaCategoriasSite()
    {
        $Categorias = Categorias::listaAll();
        $html = [];

        foreach ($Categorias as $categoria) {
            array_push($html, "<li><a href='/categories/{$categoria['idcategorias']}'>{$categoria['descategorias']}</a></li>");
        }

        file_put_contents($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."Views".DIRECTORY_SEPARATOR."categorias_menu.html", implode('', $html));

    }

    public function getProdutosRelacionadosComCategorias($Relacionados = true)
    {
        $sql = new Sql();

        if($Relacionados === true){

            return $sql->select("SELECT * FROM tb_produtos 
            WHERE idprodutos IN(
                SELECT produtos.idprodutos FROM tb_produtos produtos
                INNER JOIN tb_produtoscategorias proCategoria ON produtos.idprodutos = proCategoria.idprodutos 
                WHERE proCategoria.idcategorias = :idcategorias
            )", [
                ":idcategorias" => $this->getidcategorias()
            ]
            );

        } else {
            return $sql->select("SELECT * FROM tb_produtos 
            WHERE idprodutos NOT IN(
                SELECT produtos.idprodutos FROM tb_produtos produtos
                INNER JOIN tb_produtoscategorias proCategoria ON produtos.idprodutos = proCategoria.idprodutos 
                WHERE proCategoria.idcategorias = :idcategorias
            )", [
                ":idcategorias" => $this->getidcategorias()
            ]
            );
        }
    }

    public function addProdutos(Produtos $Produtos)
    {
        $sql = new Sql();
        $sql->query("INSERT INTO tb_produtoscategorias (idcategorias, idprodutos) VALUES(:idcategorias, :idprodutos)", [
            ":idcategorias" => $this->getidcategorias(),
            ":idprodutos" => $Produtos->getidprodutos()
        ]);
    }

    public function removeProdutos(Produtos $Produtos)
    {
        $sql = new Sql();
        $sql->query("DELETE FROM tb_produtoscategorias WHERE idcategorias = :idcategorias AND idprodutos = :idprodutos", [
            ":idcategorias" => $this->getidcategorias(),
            ":idprodutos" => $Produtos->getidprodutos()
        ]);
    }

    public function getPaginacaoDosProdutos($Paginas = 1, $QntItensProPg = 3)
    {

        $ComecarPg = ($Paginas - 1) * $QntItensProPg;

        $sql = new Sql();

        $results = $sql->select("SELECT SQL_CALC_FOUND_ROWS * 
            FROM tb_produtos produtos
            INNER JOIN tb_produtoscategorias proCategorias 
            ON produtos.idprodutos = proCategorias.idprodutos
            INNER JOIN tb_categorias categorias
            ON categorias.idcategorias = proCategorias.idcategorias
            WHERE categorias.idcategorias = :idcategorias
            LIMIT $ComecarPg, $QntItensProPg;", 
            [
                ":idcategorias" => $this->getidcategorias()
            ]
        );

        $resultsTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

        return [
            "DadosProdutos" => Produtos::listarProdutos($results),
            "Total" => (int)$resultsTotal[0]['nrtotal'],
            "PaginasGeradas" => ceil($resultsTotal[0]['nrtotal'] / $QntItensProPg)
        ];
    }

    public static function getPaginacaoDosCategoria($Paginas = 1, $QntItensProPg = 3)
    {

        $ComecarPg = ($Paginas - 1) * $QntItensProPg;

        $sql = new Sql();

        $results = $sql->select("SELECT SQL_CALC_FOUND_ROWS * 
            FROM tb_categorias ORDER BY idcategorias DESC
            LIMIT $ComecarPg, $QntItensProPg;"
        );

        $resultsTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

        return [
            "Dados" => $results,
            "Total" => (int)$resultsTotal[0]['nrtotal'],
            "PaginasGeradas" => ceil($resultsTotal[0]['nrtotal'] / $QntItensProPg)
        ];
    }

    public static function getPaginacaoDePesquisaAoCategoria($pesquisa, $Paginas = 1, $QntItensProPg = 3)
    {

        $ComecarPg = ($Paginas - 1) * $QntItensProPg;

        $sql = new Sql();

        $results = $sql->select("SELECT SQL_CALC_FOUND_ROWS * 
            FROM tb_categorias 
            WHERE descategorias LIKE :pesquisa
            ORDER BY idcategorias DESC
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
