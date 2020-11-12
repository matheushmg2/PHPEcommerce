<?php

use \Hcode\PageAdm;
use \Hcode\Models\User;
use \Hcode\Models\Produtos;

/**
 * =======================================================================================
 * 										VIA GET 
 * =======================================================================================
 */ 
$app->get('/Adm/products', function() { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();

	$Pesquisa = (isset($_GET['search'])) ? $_GET['search'] : "";
	$pagina = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

	if($Pesquisa != ''){
		$Paginacao = Produtos::getPaginacaoDePesquisaAoProdutos($Pesquisa,$pagina, 3);
	} else {
		$Paginacao = Produtos::getPaginacaoDosProdutos($pagina, 3);
	}

	// Validação que verifica se existe o números de páginas, caso não exista redireciona para a página CATEGORIAS
	if($pagina == 0 || $pagina > $Paginacao['PaginasGeradas']) {
		header("Location: /Adm/products");
		exit;
	 }

	$Paginas = [];

	for ($i=1; $i <= $Paginacao['PaginasGeradas']; $i++) { 
		array_push($Paginas, [
			'href' => '/Adm/products?'.http_build_query([
				'page' => $i,
				'search' => $Pesquisa
			]),
			'text' => $i
		]);
	}

	//$Produtos = Produtos::listaAll();

	$pagina = new PageAdm();

	$pagina->setTpl('products', [
		"Produtos" => $Paginacao['Dados'],
		'search' => $Pesquisa,
		'Paginas' => $Paginas
	]);

});

$app->get('/Adm/products/create', function() { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();

	$pagina = new PageAdm();

	$pagina->setTpl('products-create');

});

$app->get('/Adm/products/:idprodutos', function($idprodutos) { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();

	$Produtos = new Produtos();

	$Produtos->get((int)$idprodutos);
	
	$pagina = new PageAdm();

	$pagina->setTpl('products-update', ['Produtos' => $Produtos->getValores()]);

});

$app->get('/Adm/products/:idprodutos/delete', function($idprodutos) { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();

	$Produtos = new Produtos();

	$Produtos->get((int)$idprodutos);

	$Produtos->delete();

	header("Location: /Adm/products");
	exit;

});

/**
 * =======================================================================================
 * 										VIA POST 
 * =======================================================================================
 */ 

 
$app->post('/Adm/products/create', function() { 

	User::verificaLogin();

	$Produtos = new Produtos();
	$Produtos->setDados($_POST);
	$Produtos->SalvarProdutos();

	header("Location: /Adm/products");
	exit;

});

$app->post('/Adm/products/:idprodutos', function($idprodutos) { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();

	$Produtos = new Produtos();

	$Produtos->get((int)$idprodutos);

	$Produtos->setDados($_POST);
	$Produtos->SalvarProdutos();

	$Produtos->setFotos($_FILES["file"]);

	header("Location: /Adm/products");
	exit;

});