<?php

use Hcode\Models\Boletos;
use \Hcode\Models\Pedidos;
use Hcode\Models\PedidosStatus;
use \Slim\Slim;
use \Hcode\PageAdm;
use \Hcode\Models\User;

/**
 * =======================================================================================
 * 										VIA GET 
 * =======================================================================================
 */ 


$app->get('/Adm/orders/:idperdidos/delete', function($idperdidos) { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();
	
	$Pedidos = new Pedidos();

	$Pedidos->get((int)$idperdidos);

	$Pedidos->deletarPedidos();

	header("Location: /Adm/orders");
	exit;

});

$app->get('/Adm/orders/:idperdidos/status', function($idperdidos) { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();
	
	$Pedidos = new Pedidos();

	$Pedidos->get((int)$idperdidos);

	$pagina = new PageAdm();	

	$pagina->setTpl('order-status', [
		"Pedidos" => $Pedidos->getValores(),
		"Status" => PedidosStatus::listaPedidosStatdosAll(),
		'MsgErro' => Pedidos::getMsgErros(),
		'MsgSucesso' => Pedidos::getMsgSucessoRegistros()
	]);

});

$app->get('/Adm/orders/:idperdidos', function($idperdidos) { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();
	
	$Pedidos = new Pedidos();

	$Pedidos->get((int)$idperdidos);

	$Carrinho = $Pedidos->getCarrinhoDoPedidos();

	$pagina = new PageAdm();	

	$pagina->setTpl('order', [
		"Pedidos" => $Pedidos->getValores(),
		"Carrinho" => $Carrinho->getValores(),
		"Produtos" => $Carrinho->getProdutosAdicionadasAoCarrinho()
	]);

});

$app->get('/Adm/orders', function() { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();

	$Pesquisa = (isset($_GET['search'])) ? $_GET['search'] : "";
	$pagina = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

	if($Pesquisa != ''){
		$Paginacao = Pedidos::getPaginacaoDePesquisaAoPedidos($Pesquisa,$pagina, 3);
	} else {
		$Paginacao = Pedidos::getPaginacaoDosPedidos($pagina, 3);
	}

	// Validação que verifica se existe o números de páginas, caso não exista redireciona para a página CATEGORIAS
	if($pagina == 0 || $pagina > $Paginacao['PaginasGeradas']) {
		header("Location: /Adm/orders");
		exit;
	 }

	$Paginas = [];

	for ($i=1; $i <= $Paginacao['PaginasGeradas']; $i++) { 
		array_push($Paginas, [
			'href' => '/Adm/orders?'.http_build_query([
				'page' => $i,
				'search' => $Pesquisa
			]),
			'text' => $i
		]);
	}
	
	$pagina = new PageAdm();

	/*var_dump(Pedidos::listarPedidosAll());
	exit;*/

	$pagina->setTpl('orders', [
		"Pedidos" => $Paginacao['Dados'],
		'search' => $Pesquisa,
		'Paginas' => $Paginas
	]);

});

$app->get('/Adm/boleto/:idperdidos', function($idperdidos) { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();

	$Pedidos = new Pedidos();

	$Pedidos->get((int)$idperdidos);

	$Boletos = new Boletos();
	$Boletos->BoletosPronto($Pedidos->getvltotal(),$Pedidos->getidperdidos(), $Pedidos->getdespessoas(), $Pedidos->getdesenderecos(), $Pedidos->getdescidades(), $Pedidos->getdesestados(), $Pedidos->getdesdistritos(), $Pedidos->getdescodigopostal());

});
/**
 * =======================================================================================
 * 										VIA POST 
 * =======================================================================================
 */ 

$app->post('/Adm/orders/:idperdidos/status', function($idperdidos) { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();
	
	if(!isset($_POST['idstatus']) || !(int)$_POST['idstatus'] > 0) {
		Pedidos::setMsgErros("Informe o status");
		header("Location: /Adm/orders/".$idperdidos."/status");
		exit;
	}

	$Pedidos = new Pedidos();

	$Pedidos->get((int)$idperdidos);

	/*var_dump($Pedidos);
	exit;*/

	$Pedidos->settb_perdidosstatus_idstatus((int)$_POST['idstatus']);

	$Pedidos->salvarPedidos();

	Pedidos::setMsgSucessoDoRegistros("Status Atualizado com Sucesso.");
	header("Location: /Adm/orders/".$idperdidos."/status");
	exit;

});