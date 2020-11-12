<?php

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdm;
use \Hcode\Models\User;
use \Hcode\Models\Categorias;

/**
 * =======================================================================================
 * 										VIA GET 
 * =======================================================================================
 */ 
$app->get('/Adm', function() { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();
	
	$pagina = new PageAdm();

	$pagina->setTpl('index');

});

$app->get('/Adm/login', function() { // os GETs são as rotas/caminhos que estão os Arquivos
	
	$pagina = new PageAdm([
		"header" => false,
		"footer" => false
	]);

	$pagina->setTpl('login');

});



$app->get('/Adm/logout', function() { // os GETs são as rotas/caminhos que estão os Arquivos
	
	User::logout();

	header("Location: /Adm/login");
	exit;

});

$app->get('/Adm/forgot', function() { // os GETs são as rotas/caminhos que estão os Arquivos

	$pagina = new PageAdm([
		"header" => false,
		"footer" => false
	]);

	$pagina->setTpl('forgot');

});

$app->get('/Adm/forgot/sent', function() { // os GETs são as rotas/caminhos que estão os Arquivos

	$pagina = new PageAdm([
		"header" => false,
		"footer" => false
	]);

	$pagina->setTpl('forgot-sent');

});

$app->get('/Adm/forgot/reset', function() { // os GETs são as rotas/caminhos que estão os Arquivos

	$User = User::validarDescriptografiaEsqueceuASenha($_GET['code']);

	$pagina = new PageAdm([
		"header" => false,
		"footer" => false
	]);

	$pagina->setTpl('forgot-reset', array(
		"name"=>$User['despessoas'],
		"code"=>$_GET['code']
	));

});

/**
 * =======================================================================================
 * 										VIA POST 
 * =======================================================================================
 */ 

$app->post('/Adm/login', function() { // os POSTs são as rotas/caminhos que estão os Arquivos
	
	User::login($_POST['login'], $_POST['password']);

	header("Location: /Adm");
	exit;
});

$app->post('/Adm/forgot', function() { // os GETs são as rotas/caminhos que estão os Arquivos

	$User = User::getEsqueceSenha($_POST['email']);
	header("Location: /Adm/forgot/sent");
	exit;

});
