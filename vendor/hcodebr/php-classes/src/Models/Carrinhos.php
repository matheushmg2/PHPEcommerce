<?php

namespace Hcode\Models;
use Hcode\DB\Sql;
use Hcode\Models;
use Hcode\Models\User;

class Carrinhos extends Models {

    const SESSION = "Carrinhos";
    const SESSION_ERROR = "Error no Carrinhos";
    

    public static function getGerandoSession()
    {
        $Carrinho = new Carrinhos();
        if(isset($_SESSION[Carrinhos::SESSION]) && (int)$_SESSION[Carrinhos::SESSION]['idcarrinho'] > 0){

            $Carrinho->get((int)$_SESSION[Carrinhos::SESSION]['idcarrinho']);

        } else {

            $Carrinho->getGerandoSessionID();

            if(!(int)$Carrinho->getIdCarrinho() > 0) {
                $dados = [
                    "dessessaoid" => session_id()
                ];

                if(User::verificaUsuarioLogado(false)){
                    $Usuario = User::getGerandoSessionUsuario();

                    $dados['tb_usuario_idusuario'] = $Usuario->getidusuario();
                }

                $Carrinho->setDados($dados);

                $Carrinho->salvarNoCarrinho();

                $Carrinho->setDaSession();

            }

        }

        return $Carrinho;

    }

    public function setDaSession()
    {
        $_SESSION[Carrinhos::SESSION] = $this->getValores();
    }

    public function getGerandoSessionID()
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_carrinho WHERE dessessaoid = :dessessaoid", array(
            ":dessessaoid" => session_id()
        ));

        if(count($results) > 0){
            $this->setDados($results[0]);
        }

    }

    public function get($idcarrinho)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_carrinho WHERE idcarrinho = :idcarrinho", array(
            ":idcarrinho" => $idcarrinho
        ));

        if(count($results) > 0){
            $this->setDados($results[0]);
        }

    }

    public function salvarNoCarrinho()
    {
        $sql = new Sql();
        $results = $sql->select("CALL sp_carrinho_salvo(:idcarrinho, :dessessaoid, :tb_usuario_idusuario, :descodigopostal, :vlfrete, :nrdias)", array(
            ":idcarrinho" => $this->getidcarrinho(),
            ":dessessaoid" => $this->getdessessaoid(),
            ":tb_usuario_idusuario" => $this->gettb_usuario_idusuario(),
            ":descodigopostal" => $this->getdescodigopostal(),
            ":vlfrete" => $this->getvlfrete(),
            ":nrdias" => $this->getnrdias()
        ));

        $this->setDados($results[0]);
    }

    public function adicionarProdutosNoCarrinho(Produtos $Produtos)
    {
        $sql = new Sql();
        $sql->query("INSERT INTO tb_carrinhoprodutos (tb_carrinho_idcarrinho, tb_produtos_idprodutos) VALUES(:tb_carrinho_idcarrinho, :tb_produtos_idprodutos)", [
            ":tb_carrinho_idcarrinho" => $this->getidcarrinho(),
            ":tb_produtos_idprodutos" => $Produtos->getidprodutos()
        ]);

        //$this->atualizarFrete();
        $this->getCalcularTotal();
    }

    public function removerProdutosNoCarrinho(Produtos $Produtos, $all = false)
    {
        $sql = new Sql();
        if($all === true) {
            $sql->query("UPDATE tb_carrinhoprodutos SET dtremover = NOW() 
                WHERE tb_carrinho_idcarrinho = :tb_carrinho_idcarrinho AND tb_produtos_idprodutos = :tb_produtos_idprodutos AND dtremover IS NULL", 
                [
                    ":tb_carrinho_idcarrinho" => $this->getidcarrinho(),
                    ":tb_produtos_idprodutos" => $Produtos->getidprodutos()
                ]);
        } else {
            $sql->query("UPDATE tb_carrinhoprodutos SET dtremover = NOW() 
                WHERE tb_carrinho_idcarrinho = :tb_carrinho_idcarrinho AND tb_produtos_idprodutos = :tb_produtos_idprodutos AND dtremover IS NULL LIMIT 1", 
                [
                    ":tb_carrinho_idcarrinho" => $this->getidcarrinho(),
                    ":tb_produtos_idprodutos" => $Produtos->getidprodutos()
                ]);
        }
        //$this->atualizarFrete();
        $this->getCalcularTotal();
    }

    public function getProdutosAdicionadasAoCarrinho()
    {
        $sql = new Sql();

        $results = $sql->select("SELECT produtos.idprodutos, produtos.desprodutos, produtos.vlpreco, produtos.vllargura, produtos.vlaltura, produtos.vlcomprimento, produtos.vlpeso, produtos.desurl,COUNT(*) AS qnt, SUM(produtos.vlpreco) AS total
        FROM tb_carrinhoprodutos cartProdutos 
        INNER JOIN tb_produtos produtos ON cartProdutos.tb_produtos_idprodutos = produtos.idprodutos
        WHERE cartProdutos.tb_carrinho_idcarrinho = :tb_carrinho_idcarrinho AND cartProdutos.dtremover IS NULL 
        GROUP BY produtos.idprodutos, produtos.desprodutos, produtos.vlpreco, produtos.vllargura, produtos.vlaltura, produtos.vlcomprimento, produtos.vlpeso, produtos.desurl
        ORDER BY produtos.desprodutos", [":tb_carrinho_idcarrinho" => $this->getidcarrinho()]);

        return Produtos::listarProdutos($results);
    }

    public function getTotalDeProdutosNoCarrinho()
    {
        $sql = new Sql();

        $results = $sql->select("SELECT SUM(produtos.vlpreco) AS preco, SUM(produtos.vllargura) AS largura, SUM(produtos.vlaltura) AS altura, 
        SUM(produtos.vlcomprimento) AS comprimento, SUM(produtos.vlpeso) AS peso, COUNT(*) AS qntTotal
        FROM tb_produtos produtos
        INNER JOIN tb_carrinhoprodutos cartProdutos
        ON produtos.idprodutos = cartProdutos.tb_produtos_idprodutos
        WHERE cartProdutos.tb_carrinho_idcarrinho = :tb_carrinho_idcarrinho AND cartProdutos.dtremover IS NULL", [
            ":tb_carrinho_idcarrinho" => $this->getidcarrinho()
        ]);

        if(count($results) > 0){
            return $results[0];
        } else {
            return [];
        }
    }

    public function setFretesProdutos($codigoPostal)
    {
        $codigoPostal = str_replace('-', '', $codigoPostal);

        $totals = $this->getTotalDeProdutosNoCarrinho();

        if($totals['qntTotal'] > 0){

            if ($totals['altura'] < 2) $totals['altura'] = 2;
            if ($totals['comprimento'] < 16) $totals['comprimento'] = 16;

            $qs = http_build_query([
                'nCdEmpresa'=>'',
                'sDsSenha'=>'',
                'nCdServico'=>'40010',
                'sCepOrigem'=>'35350000',
                'sCepDestino'=>$codigoPostal,
                'nVlPeso'=>$totals['peso'],
                'nCdFormato'=>'1',
                'nVlComprimento'=>$totals['comprimento'],
                'nVlAltura'=>$totals['altura'],
                'nVlLargura'=>$totals['largura'],
                'nVlDiametro'=>'0',
                'sCdMaoPropria'=>'S',
                'nVlValorDeclarado'=>$totals['preco'],
                'sCdAvisoRecebimento'=>'S'
            ]);

            $xml = simplexml_load_file("http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx/CalcPrecoPrazo?".$qs);

            $results = $xml->Servicos->cServico;

            if($results->MsgErro != ''){
                Carrinhos::setMsgErros($results->MsgErro);
            } else {
                Carrinhos::limparMsgErros();
            }

            $this->setnrdias($results->PrazoEntrega);
            $this->setvlfrete(Carrinhos::formatarValoresDecimal($results->Valor));
            $this->setdescodigopostal($codigoPostal);

            $this->salvarNoCarrinho();

            return $results;

        } else {

        }
    }

    public static function formatarValoresDecimal($valores)
    {
        $valores = str_replace('.', '', $valores);
        return str_replace(',', '.', $valores);
    }

    public static function setMsgErros($msg)
    {
        $_SESSION[Carrinhos::SESSION_ERROR] = $msg;
    }

    public static function getMsgErros()
    {
        $msg = (isset($_SESSION[Carrinhos::SESSION_ERROR])) ? $_SESSION[Carrinhos::SESSION_ERROR] : "";

        Carrinhos::limparMsgErros();

        return $msg;
    }

    public static function limparMsgErros()
    {
        $_SESSION[Carrinhos::SESSION_ERROR] = NULL;
    }

    public function atualizarFrete()
    {
        if($this->getdescodigopostal() != ''){
            $this->setFretesProdutos($this->getdescodigopostal());
        }
    }

    public function getValores()
    {
        $this->getCalcularTotal();

        return parent::getValores();
    }

    public function getCalcularTotal()
    {

        $this->atualizarFrete();

        $totals = $this->getTotalDeProdutosNoCarrinho();

        $this->setSubTotal($totals['preco']);
        $this->setTotal($totals['preco'] + $this->getvlfrete());
    }

}


