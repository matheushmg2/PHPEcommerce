<?php

use \Hcode\PageAdm;
use \Hcode\Models\User;
use \Hcode\Models\Categorias;
use Hcode\Models\Produtos;

/**
 * =======================================================================================
 * 										VIA GET 
 * =======================================================================================
 */ 

$app->get('/Adm/categories', function() { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();

	$Pesquisa = (isset($_GET['search'])) ? $_GET['search'] : "";
	$pagina = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

	if($Pesquisa != ''){
		$Paginacao = Categorias::getPaginacaoDePesquisaAoCategoria($Pesquisa,$pagina, 3);
	} else {
		$Paginacao = Categorias::getPaginacaoDosCategoria($pagina, 3);
	}

	// Validação que verifica se existe o números de páginas, caso não exista redireciona para a página CATEGORIAS
	if($pagina == 0 || $pagina > $Paginacao['PaginasGeradas']) {
		header("Location: /Adm/categories");
		exit;
	 }

	$Paginas = [];

	for ($i=1; $i <= $Paginacao['PaginasGeradas']; $i++) { 
		array_push($Paginas, [
			'href' => '/Adm/categories?'.http_build_query([
				'page' => $i,
				'search' => $Pesquisa
			]),
			'text' => $i
		]);
	}

	//$Categorias = Categorias::listaAll();

	$pagina = new PageAdm();

	$pagina->setTpl('categories', [
		"Categorias" => $Paginacao['Dados'],
		'search' => $Pesquisa,
		'Paginas' => $Paginas
	]);

});

$app->get('/Adm/categories/create', function() { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();

	$pagina = new PageAdm();

	$pagina->setTpl('categories-create');

});

$app->get('/Adm/categories/:idcategorias/delete', function($idcategorias) { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();

	$Categorias = new Categorias();
	$Categorias->get((int)$idcategorias);
	$Categorias->delete();
	header("Location: /Adm/categories");
	exit;

});

$app->get('/Adm/categories/:idcategorias', function($idcategorias) { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();

	$Categorias = new Categorias();
	$Categorias->get((int)$idcategorias);
	
	$pagina = new PageAdm();

	$pagina->setTpl('categories-update', ['Categorias' => $Categorias->getValores()]);

});

$app->get('/Adm/categories/:idcategorias/products', function($idcategorias) { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();

	$Categorias = new Categorias();
	$Categorias->get((int)$idcategorias);
	
	$pagina = new PageAdm();

	$pagina->setTpl('categories-products', [
		'Categorias' => $Categorias->getValores(),
		'ProdutosRelacionado' => $Categorias->getProdutosRelacionadosComCategorias(),
		'ProdutosNaoRelacionado' => $Categorias->getProdutosRelacionadosComCategorias(false)
		]);
		
});


$app->get('/Adm/categories/:idcategorias/products/:idprodutos/add', function($idcategorias, $idprodutos) { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();

	$Categorias = new Categorias();
	$Categorias->get((int)$idcategorias);

	$Produtos = new Produtos();
	$Produtos->get((int)$idprodutos);

	$Categorias->addProdutos($Produtos);

	header("Location: /Adm/categories/".$idcategorias."/products");
	exit;
		
});

$app->get('/Adm/categories/:idcategorias/products/:idprodutos/remove', function($idcategorias, $idprodutos) { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();

	$Categorias = new Categorias();
	$Categorias->get((int)$idcategorias);

	$Produtos = new Produtos();
	$Produtos->get((int)$idprodutos);

	$Categorias->removeProdutos($Produtos);

	header("Location: /Adm/categories/".$idcategorias."/products");
	exit;
		
});

/**
 * =======================================================================================
 * 										VIA POST 
 * =======================================================================================
 */ 

$app->post('/Adm/categories/create', function() { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();

	$Categorias = new Categorias();
	$Categorias->setDados($_POST);
	$Categorias->SalvarCategorias();


	header("Location: /Adm/categories");
	exit;

});

$app->post('/Adm/categories/:idcategorias', function($idcategorias) { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();

	$Categorias = new Categorias();
	$Categorias->get((int)$idcategorias);

	$Categorias->setDados($_POST);

	$Categorias->SalvarCategorias();

	header("Location: /Adm/categories");
	exit;

});