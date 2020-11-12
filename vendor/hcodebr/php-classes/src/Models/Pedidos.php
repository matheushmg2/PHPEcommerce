<?php

namespace Hcode\Models;
use Hcode\DB\Sql;
use Hcode\Models;

class Pedidos extends Models {

    const ERROR = "UsuarioError";
    const SUCESSO_REGISTROS = "UsuarioSucessoRegistros";

    public function salvarPedidos()
    {
        $sql = new Sql();
        $results = $sql->select("CALL sp_pedidos_salvo(:idperdidos, :tb_carrinho_idcarrinho, :tb_usuario_idusuario, :tb_perdidosstatus_idstatus, :tb_enderecos_idenderecos, :vltotal)", array(
            ":idperdidos" => $this->getidperdidos(),
            ":tb_carrinho_idcarrinho" => $this->gettb_carrinho_idcarrinho(),
            ":tb_usuario_idusuario" => $this->gettb_usuario_idusuario(),
            ":tb_perdidosstatus_idstatus" => $this->gettb_perdidosstatus_idstatus(),
            ":tb_enderecos_idenderecos" => $this->gettb_enderecos_idenderecos(),
            ":vltotal" => $this->getvltotal()
        ));

        if(count($results) > 0){
            $this->setDados($results[0]);
        }
    }

    public function get($idperdidos)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_perdidos pedidos 
        INNER JOIN tb_perdidosstatus pstatus ON pedidos.tb_perdidosstatus_idstatus = pstatus.idstatus
        INNER JOIN tb_carrinho carrinho ON pedidos.tb_carrinho_idcarrinho = carrinho.idcarrinho
        INNER JOIN tb_usuario usuario ON pedidos.tb_usuario_idusuario = usuario.idusuario
        INNER JOIN tb_enderecos endereco ON pedidos.tb_enderecos_idenderecos = endereco.idenderecos
        INNER JOIN tb_pessoas pessoas ON pessoas.idpessoas = usuario.tb_pessoas_idpessoas
        WHERE pedidos.idperdidos = :idperdidos", array(
            ":idperdidos" => $idperdidos
        ));

        if(count($results) > 0){
            $this->setDados($results[0]);
        }

    }

    public static function listarPedidosAll()
    {
        $sql = new Sql();
        return $sql->select("SELECT * FROM tb_perdidos pedidos 
        INNER JOIN tb_perdidosstatus pstatus ON pedidos.tb_perdidosstatus_idstatus = pstatus.idstatus
        INNER JOIN tb_carrinho carrinho ON pedidos.tb_carrinho_idcarrinho = carrinho.idcarrinho
        INNER JOIN tb_usuario usuario ON pedidos.tb_usuario_idusuario = usuario.idusuario
        INNER JOIN tb_enderecos endereco ON pedidos.tb_enderecos_idenderecos = endereco.idenderecos
        INNER JOIN tb_pessoas pessoas ON pessoas.idpessoas = usuario.tb_pessoas_idpessoas
        ORDER BY pedidos.dtregistros");
    }
    
    public function deletarPedidos()
    {
        $sql = new Sql();
        $sql->query("DELETE FROM tb_perdidos WHERE idperdidos = :idperdidos", [
            ":idperdidos" => $this->getidperdidos()
        ]);
    }

    public function getCarrinhoDoPedidos()
    {
        $Carrinho = new Carrinhos();

        $Carrinho->get((int) $this->gettb_carrinho_idcarrinho());

        return $Carrinho;
    }

    // ========================================================= //
    public static function setMsgErros($msg)
    {
        $_SESSION[Pedidos::ERROR] = $msg;
    }

    public static function getMsgErros()
    {
        $msg = (isset($_SESSION[Pedidos::ERROR])) ? $_SESSION[Pedidos::ERROR] : "";

        Pedidos::limparMsgErros();

        return $msg;
    }

    public static function limparMsgErros()
    {
        $_SESSION[Pedidos::ERROR] = NULL;
    }

    // ========================================================= //
    public static function setMsgSucessoDoRegistros($msg)
    {
        $_SESSION[Pedidos::SUCESSO_REGISTROS] = $msg;
    }

    public static function getMsgSucessoRegistros()
    {
        $msg = (isset($_SESSION[Pedidos::SUCESSO_REGISTROS])) ? $_SESSION[User::SUCESSO_REGISTROS] : "";

        Pedidos::limparMsgSucessoRegistros();

        return $msg;
    }

    public static function limparMsgSucessoRegistros()
    {
        $_SESSION[Pedidos::SUCESSO_REGISTROS] = NULL;
    }

    public static function getPaginacaoDosPedidos($Paginas = 1, $QntItensProPg = 3)
    {

        $ComecarPg = ($Paginas - 1) * $QntItensProPg;

        $sql = new Sql();

        $results = $sql->select("SELECT SQL_CALC_FOUND_ROWS * 
            FROM tb_perdidos pedidos 
            INNER JOIN tb_perdidosstatus pstatus ON pedidos.tb_perdidosstatus_idstatus = pstatus.idstatus
            INNER JOIN tb_carrinho carrinho ON pedidos.tb_carrinho_idcarrinho = carrinho.idcarrinho
            INNER JOIN tb_usuario usuario ON pedidos.tb_usuario_idusuario = usuario.idusuario
            INNER JOIN tb_enderecos endereco ON pedidos.tb_enderecos_idenderecos = endereco.idenderecos
            INNER JOIN tb_pessoas pessoas ON pessoas.idpessoas = usuario.tb_pessoas_idpessoas
            ORDER BY pedidos.dtregistros
            LIMIT $ComecarPg, $QntItensProPg;"
        );

        $resultsTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

        return [
            "Dados" => $results,
            "Total" => (int)$resultsTotal[0]['nrtotal'],
            "PaginasGeradas" => ceil($resultsTotal[0]['nrtotal'] / $QntItensProPg)
        ];
    }

    public static function getPaginacaoDePesquisaAoPedidos($pesquisa, $Paginas = 1, $QntItensProPg = 3)
    {

        $ComecarPg = ($Paginas - 1) * $QntItensProPg;

        $sql = new Sql();

        $results = $sql->select("SELECT SQL_CALC_FOUND_ROWS * 
            FROM tb_perdidos pedidos 
            INNER JOIN tb_perdidosstatus pstatus ON pedidos.tb_perdidosstatus_idstatus = pstatus.idstatus
            INNER JOIN tb_carrinho carrinho ON pedidos.tb_carrinho_idcarrinho = carrinho.idcarrinho
            INNER JOIN tb_usuario usuario ON pedidos.tb_usuario_idusuario = usuario.idusuario
            INNER JOIN tb_enderecos endereco ON pedidos.tb_enderecos_idenderecos = endereco.idenderecos
            INNER JOIN tb_pessoas pessoas ON pessoas.idpessoas = usuario.tb_pessoas_idpessoas
            WHERE pessoas.despessoas LIKE :pesquisa OR pstatus.desstatus LIKE :pesquisa
            ORDER BY pedidos.dtregistros 
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