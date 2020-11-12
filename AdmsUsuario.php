<?php

use \Hcode\PageAdm;
use \Hcode\Models\User;

/**
 * =======================================================================================
 * 										VIA GET 
 * =======================================================================================
 */ 

$app->get('/Adm/users', function() { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();

	$Pesquisa = (isset($_GET['search'])) ? $_GET['search'] : "";
	$pagina = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
	//var_dump($Users);

	if($Pesquisa != ''){
		$Paginacao = User::getPaginacaoDePesquisaAoUsuarios($Pesquisa,$pagina, 5);
	} else {
		$Paginacao = User::getPaginacaoDosUsuarios($pagina, 5);
	}

	$Paginas = [];

	for ($i=1; $i <= $Paginacao['PaginasGeradas']; $i++) { 
		array_push($Paginas, [
			'href' => '/Adm/users?'.http_build_query([
				'page' => $i,
				'search' => $Pesquisa
			]),
			'text' => $i
		]);
	}

	$pagina = new PageAdm();

	$pagina->setTpl('users', [
		'users' => $Paginacao['Dados'],
		'search' => $Pesquisa,
		'Paginas' => $Paginas
		]); // O setTpl -> Ele chama exatamente os nomes dos Arquivos / templates users.html

});

$app->get('/Adm/users/create', function() { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();
	
	$pagina = new PageAdm();

	$pagina->setTpl('users-create'); // O setTpl -> Ele chama exatamente os nomes dos Arquivos / templates users-create.html

});

$app->get('/Adm/users/:idusuario/delete', function($idusuario) { // os GETs são as rotas/caminhos, Nesse seria para DELETAR
	
	User::verificaLogin();

	$User = new User();
	$User->get((int)$idusuario);
	$User->delete();
	header("Location: /Adm/users");
	exit;

});


$app->get('/Adm/users/:idusuario/password', function($idusuario) { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();

	$User = new User();
	$User->get((int)$idusuario);
	
	$pagina = new PageAdm();

	$pagina->setTpl('users-password', array(
			'user' => $User->getValores(),
			'MsgError' => User::getMsgErros(),
			'MsgSuccess' => User::getMsgSucessoRegistros()
		)); // O setTpl -> Ele chama exatamente os nomes dos Arquivos / templates users-update.html

});

$app->get('/Adm/users/:idusuario', function($idusuario) { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin();

	$User = new User();
	$User->get((int)$idusuario);
	
	$pagina = new PageAdm();

	$pagina->setTpl('users-update', array(
			'user' => $User->getValores()
		)); // O setTpl -> Ele chama exatamente os nomes dos Arquivos / templates users-update.html

});



/**
 * =======================================================================================
 * 										VIA POST 
 * =======================================================================================
 */ 

$app->post('/Adm/users/create', function() { // os POSTs são as rotas/caminhos que estão os Arquivos
	
	User::verificaLogin();

	$User = new User();
/*
	$senha = password_hash($_POST['despassword'], PASSWORD_DEFAULT);

	$_POST['despassword'] = $senha;*/

	//var_dump($_POST);

	$_POST['inadmin'] = (isset($_POST['inadmin'])) ? 1 : 0;

	$User->setDados($_POST);

	$User->createSalvar();

	header("Location: /Adm/users");
	exit;

});

$app->post('/Adm/users/:idusuario', function($idusuario) { // os POSTs são as rotas/caminhos que estão os Arquivos
	
	User::verificaLogin();

	$User = new User();
	$_POST['inadmin'] = (isset($_POST['inadmin'])) ? 1 : 0;

	$User->get((int)$idusuario);
	
	$User->setDados($_POST);
	//echo "(int) idusuario";
	//var_dump((int)$idusuario);
	//echo "<br> _POST";
	//var_dump($_POST);
	$User->updateSalvar();

	header("Location: /Adm/users");
	exit;

});

$app->post('/Adm/users/:idusuario/password', function($idusuario) { // os POSTs são as rotas/caminhos que estão os Arquivos
	
	User::verificaLogin();

	if(!isset($_POST['despassword']) || $_POST['despassword'] === ''){
		User::setMsgErros("Preencha a nova senha.");
		header("Location: /Adm/users/$idusuario/password");
		exit;
	}

	if(!isset($_POST['despassword-confirm']) || $_POST['despassword-confirm'] === ''){
		User::setMsgErros("Preencha a nova senha de confirmação.");
		header("Location: /Adm/users/$idusuario/password");
		exit;
	}

	if($_POST['despassword'] !== $_POST['despassword-confirm']){
		User::setMsgErros("Ambas senhas precisam ser iguais.");
		header("Location: /Adm/users/$idusuario/password");
		exit;
	}

// despassword
// despassword-confirm

	$User = new User();

	$User->get((int)$idusuario);
	
	$User->setPassword(User::getPasswordHash($_POST['despassword']));
	//$User->updateSalvar();
	User::setMsgSucessoDoRegistros("Senha alterada com sucesso.");
	header("Location: /Adm/users/$idusuario/password");
	exit;

});