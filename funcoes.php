<?php

use \Hcode\Models\User;

use Hcode\Models\Carrinhos;

function formatarValores($valores)
{
    if(!$valores > 0) $valores = 0;

    return number_format($valores, 2, ",", ".");
}

function formatarDatas($datas){

    return date('d/m/Y', strtotime($datas));

}

function verificaLogin($inadmin = true){
    return User::verificaUsuarioLogado($inadmin);
}

function getNomeUsuario(){
    $User = User::getGerandoSessionUsuario();

    //var_dump($User->gettb_pessoas_idpessoas()); exit;
    /*
    var_dump($User->getNomePessoaUsuario((int)$User->gettb_pessoas_idpessoas()));
    exit;*/
    return $User->getdespessoas();
    //return $User->getNomePessoaUsuario((int)$User->gettb_pessoas_idpessoas());
}

function getCarrinhoQnt(){
    $Carrinho = Carrinhos::getGerandoSession();
    $Totais =  $Carrinho->getTotalDeProdutosNoCarrinho();

    return $Totais['qntTotal'];
}

function getCarrinhoSubTotal(){
    $Carrinho = Carrinhos::getGerandoSession();
    $Totais =  $Carrinho->getTotalDeProdutosNoCarrinho();

    return formatarValores($Totais['preco']);
}

 ?>