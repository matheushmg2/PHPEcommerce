<?php

use Hcode\Models\Boletos;
use \Hcode\Models\Carrinhos;
use \Hcode\Page;
use \Hcode\Models\Categorias;
use \Hcode\Models\Endereco;
use \Hcode\Models\Pedidos;
use \Hcode\Models\PedidosStatus;
use \Hcode\Models\Produtos;
use \Hcode\Models\User;

/**
 * =======================================================================================
 * 										VIA GET 
 * =======================================================================================
 */ 


$app->get('/', function() { // os GETs são as rotas/caminhos que estão os Arquivos
    
    $Produtos = Produtos::listaAll();

	$pagina = new Page();

	$pagina->setTpl('index', [
        "Produtos" => Produtos::listarProdutos($Produtos)
    ]);

});


$app->get('/categories/:idcategorias', function($idcategorias) { // os GETs são as rotas/caminhos que estão os Arquivos

	$pagina = (isset($_GET['pg'])) ? (int)$_GET['pg'] : 1 ;

	$Categorias = new Categorias();
	$Categorias->get((int)$idcategorias);

	$Paginacao = $Categorias->getPaginacaoDosProdutos($pagina);
	
	$Paginacoes = [];

	for ($i=1; $i <= $Paginacao['PaginasGeradas'] ; $i++) { 
		array_push($Paginacoes, [
			'links' => '/categories/'.$Categorias->getidcategorias().'?pg='.$i,
			'pg' => $i
		]);
	}

	$pagina = new Page();

	$pagina->setTpl('category', [
		'Categorias' => $Categorias->getValores(),
		'Produtos' => $Paginacao['DadosProdutos'],
		'Paginacoes' => $Paginacoes
		]);

});

$app->get('/products/:desurl', function($desurl) { // os GETs são as rotas/caminhos que estão os Arquivos
    
	$Produtos = new Produtos();
	
	$Produtos->getFromUrl($desurl);

	$pagina = new Page();

	$pagina->setTpl('product-detail', [
		"Produtos" => $Produtos->getValores(),
		"Categorias" => $Produtos->getCategoriasPertencentes()
    ]);

});

$app->get('/cart', function() { // os GETs são as rotas/caminhos que estão os Arquivos

	$Carrinho = Carrinhos::getGerandoSession();

	$pagina = new Page();

	var_dump($Carrinho->getValores());

	$pagina->setTpl('cart', [
		"Carrinho" => $Carrinho->getValores(),
		"Produtos" => $Carrinho->getProdutosAdicionadasAoCarrinho(),
		'MsgErro' => Carrinhos::getMsgErros()
	]);


});

$app->get('/cart/:idprodutos/add', function($idprodutos) { // os GETs são as rotas/caminhos que estão os Arquivos

	$Produtos = new Produtos();

	$Produtos->get((int)$idprodutos);

	$Carrinho = Carrinhos::getGerandoSession();

	$Carrinho->adicionarProdutosNoCarrinho($Produtos);

	header("Location: /cart");
	exit;

});

$app->get('/cart/:idprodutos/minus', function($idprodutos) { // os GETs são as rotas/caminhos que estão os Arquivos

	$Produtos = new Produtos();

	$Produtos->get((int)$idprodutos);

	$Carrinho = Carrinhos::getGerandoSession();

	$Carrinho->removerProdutosNoCarrinho($Produtos);

	header("Location: /cart");
	exit;

});

$app->get('/cart/:idprodutos/remove', function($idprodutos) { // os GETs são as rotas/caminhos que estão os Arquivos

	$Produtos = new Produtos();

	$Produtos->get((int)$idprodutos);

	$Carrinho = Carrinhos::getGerandoSession();

	$Carrinho->removerProdutosNoCarrinho($Produtos, true);

	header("Location: /cart");
	exit;

});

$app->get('/checkout', function() { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin(false);

	$Endereco = new Endereco();
	$Carrinho = Carrinhos::getGerandoSession();

	if(!isset($_GET['descodigopostal'])){
		$_GET['descodigopostal'] = $Carrinho->getdescodigopostal();
	}

/*
	var_dump($_GET);
	exit;*/

	if(isset($_GET['descodigopostal'])) {
		$Endereco->cargaDeCEP($_GET['descodigopostal']);

		$Carrinho->setdescodigopostal($_GET['descodigopostal']);

		$Carrinho->salvarNoCarrinho();

		$Carrinho->getCalcularTotal();
	}	

	if(!$Endereco->getdesenderecos()) $Endereco->setdesenderecos('');
	if(!$Endereco->getdescomplemento()) $Endereco->setdescomplemento('');
	if(!$Endereco->getdescidades()) $Endereco->setdescidades('');
	if(!$Endereco->getdesestados()) $Endereco->setdesestados('');
	if(!$Endereco->getdespais()) $Endereco->setdespais('');
	if(!$Endereco->getdescodigopostal()) $Endereco->setdescodigopostal('');
	if(!$Endereco->getdesdistritos()) $Endereco->setdesdistritos('');
/*

	 echo "<br>";
	 echo $Carrinho->getdescodigopostal();

	echo "<br>get <br>";
	var_dump($_GET);
	exit;*/

	$pagina = new Page();

	$pagina->setTpl('checkout', [
		"Carrinho" => $Carrinho->getValores(),
		"Endereco" => $Endereco->getValores(),
		"Produtos" => $Carrinho->getProdutosAdicionadasAoCarrinho(),
		"MsgErro" => Endereco::getMsgErros()
	]);

});

$app->get('/login', function() { // os GETs são as rotas/caminhos que estão os Arquivos

	$pagina = new Page();

	$pagina->setTpl('login', [
		"MsgError" => User::getMsgErros(),
		"MsgErrorRegistros" => User::getMsgErrosRegistros(),
		"registrosValores" => (isset($_SESSION['registrosValores'])) ? $_SESSION['registrosValores'] : [
		'nome' => '', 'login' => '', 'email' => '', 'phone' => '']
	]);

});

$app->get('/logout', function() { // os GETs são as rotas/caminhos que estão os Arquivos

	User::logout();

	header("Location: /login");
	exit;

});

$app->get('/profile', function() { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin(false);

	$User = User::getGerandoSessionUsuario();

	$pagina = new Page();

	$pagina->setTpl('profile', [
		"Usuario" => $User->getValores(),
		"MsgPerfil" => User::getMsgSucessoRegistros(),
		"MsgErrorPerfil" => User::getMsgErros()
	]);

});

$app->get('/order/:idperdidos', function($idperdidos) { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin(false);

	$Pedidos = new Pedidos();

	$Pedidos->get((int)$idperdidos);

	$pagina = new Page();

	$pagina->setTpl('payment', [
		'Pedidos' => $Pedidos->getValores()
	]);

});

$app->get('/boleto/:idperdidos', function($idperdidos) { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin(false);

	$Pedidos = new Pedidos();

	$Pedidos->get((int)$idperdidos);

	$Boletos = new Boletos();
	$Boletos->BoletosPronto($Pedidos->getvltotal(),$Pedidos->getidperdidos(), $Pedidos->getdespessoas(), $Pedidos->getdesenderecos(), $Pedidos->getdescidades(), $Pedidos->getdesestados(), $Pedidos->getdesdistritos(), $Pedidos->getdescodigopostal());

});

$app->get('/profile/orders', function() { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin(false);

	$User = User::getGerandoSessionUsuario();

	$pagina = new Page();

	//var_dump($User->getPedidosUsuario());

	$pagina->setTpl('profile-orders', [
		"UsuarioPedidos" => $User->getPedidosUsuario()
	]);

});

$app->get('/profile/orders/:idperdidos', function($idperdidos) { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin(false);

	$Pedidos = new Pedidos();

	$Pedidos->get((int)$idperdidos);

	$Carrinho = new Carrinhos();

	$Carrinho->get((int)$Pedidos->getidcarrinho());

	$Carrinho->getCalcularTotal();
/*
	var_dump($Carrinho->getProdutosAdicionadasAoCarrinho());
	exit;*/

	$pagina = new Page();

	$pagina->setTpl('profile-orders-detail', [
		"Pedidos" => $Pedidos->getValores(),
		"Carrinho" => $Carrinho->getValores(),
		"Produtos" => $Carrinho->getProdutosAdicionadasAoCarrinho()
	]);

});

$app->get('/profile/change-password', function() { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin(false);

	//$User = User::getGerandoSessionUsuario();

	$pagina = new Page();

	$pagina->setTpl('profile-change-password', [
		"PasswdError" => User::getMsgErros(),
		"PasswdSuccess" => User::getMsgSucessoRegistros()
	]);

});


/**
 * =======================================================================================
 * 										VIA POST 
 * =======================================================================================
 */ 

$app->post('/cart/freight', function() { 

	$Carrinho = Carrinhos::getGerandoSession();

	$Carrinho->setFretesProdutos($_POST['zipcode']);

	header("Location: /cart");
	exit;

});


$app->post('/login', function() { 

	try {
		User::login($_POST['login'], $_POST['password']);
		header("Location: /checkout");
		exit;
		
	} catch (\Exception $e) {
		User::setMsgErros($e->getMessage());
	}

	header("Location: /login");
	exit;
});

$app->post('/register', function() {


	$_SESSION['registrosValores'] = $_POST;

	//var_dump($_POST);
	
	if(
		(!isset($_POST['nome']) || $_POST['nome'] == '') ||
		(!isset($_POST['login']) || $_POST['login'] == '') ||
		(!isset($_POST['password']) || $_POST['password'] == '') ||
		(!isset($_POST['email']) || $_POST['email'] == '') ||
		(!isset($_POST['phone']) || $_POST['phone'] == '')
	) {
		User::setMsgErrosDoRegistros("Preenchar os campo!");
		var_dump($_POST);
		header("Location: /login");
		exit;
	} 
	
	if(User::verificarLoginExistente($_POST['login']) === true) {
		User::setMsgErrosDoRegistros("Login Já existente!");
		var_dump($_POST);
		header("Location: /login");
		exit;
	}

	$User = new User();

	$User->setDados([
		'inadmin' => 0,
		'despessoas' => $_POST['nome'],
		'deslogin' => $_POST['login'],
		'despassword' => $_POST['password'],
		'desemail' => $_POST['email'],
		'nrphone' => $_POST['phone']
	]);

	$User->createSalvar();

	User::login($_POST['login'], $_POST['password']);

	header("Location: /checkout");
	exit;

});

$app->post('/profile', function() {

	User::verificaLogin(false);

	if(!isset($_POST['despessoas']) || $_POST['despessoas'] === ''){
		User::setMsgErros("Preencha o seu Nome");
		header("Location: /profile");
		exit;
	}
	if(!isset($_POST['desemail']) || $_POST['desemail'] === ''){
		User::setMsgErros("Preencha o seu E-mail");
		header("Location: /profile");
		exit;
	}

	$User = User::getGerandoSessionUsuario();

	if ($_POST['desemail'] !== $User->getdesemail()) {
		if(User::verificarEmailExistente($_POST['desemail']) === true) {
			User::setMsgErros("E-mail já cadastrado.");
			header("Location: /profile");
		exit;
		}
	}

	$_POST['inadmin'] = $User->getinadmin();
	$_POST['deslogin'] = $User->getdeslogin();
	$_POST['despassword'] = $User->getdespassword();

	$User->setDados($_POST);

	var_dump($_POST);
	exit;

	$User->updateSalvar();

	User::setMsgSucessoDoRegistros("Dados alterador com sucesso!");

	header("Location: /profile");
	exit;

});

$app->post('/checkout', function() { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin(false);

	if(!isset($_POST['descodigopostal']) || $_POST['descodigopostal'] === '') {
		Endereco::setMsgErros("Informe o CEP");
		header("Location: /checkout");
		exit;
	}

	if(!isset($_POST['desenderecos']) || $_POST['desenderecos'] === '') {
		Endereco::setMsgErros("Informe o Enderecos");
		header("Location: /checkout");
		exit;
	}
	if(!isset($_POST['descomplemento']) || $_POST['descomplemento'] === '') {
		Endereco::setMsgErros("Informe o Complemento");
		header("Location: /checkout");
		exit;
	}
	if(!isset($_POST['desdistritos']) || $_POST['desdistritos'] === '') {
		Endereco::setMsgErros("Informe o Bairro");
		header("Location: /checkout");
		exit;
	}
	if(!isset($_POST['descidades']) || $_POST['descidades'] === '') {
		Endereco::setMsgErros("Informe a Cidade");
		header("Location: /checkout");
		exit;
	}
	if(!isset($_POST['desestados']) || $_POST['desestados'] === '') {
		Endereco::setMsgErros("Informe a sigla do Estados");
		header("Location: /checkout");
		exit;
	}

	if(!isset($_POST['despais']) || $_POST['despais'] === '') {
		Endereco::setMsgErros("Informe o País");
		header("Location: /checkout");
		exit;
	}

	$User = User::getGerandoSessionUsuario();

	$Endereco = new Endereco();

	$_POST['tb_pessoas_idpessoas'] = $User->getidusuario();

	$Endereco->setDados($_POST);
/*
	echo "<br> post <br>";
	var_dump($_POST);
	echo "<br> Endereco <br>";
	var_dump($Endereco);
	exit;*/

	$Endereco->salvarEnderecoComUsuario();

	$Carrinho = Carrinhos::getGerandoSession();
	$Carrinho->getCalcularTotal();

	$total = $Carrinho->getTotal();

	$Pedidos = new Pedidos();

	$Pedidos->setDados([
		'tb_carrinho_idcarrinho' => $Carrinho->getidcarrinho(),
		'tb_usuario_idusuario' => $User->getidusuario(),
		'tb_perdidosstatus_idstatus' => PedidosStatus::EM_ABERTO,
		'tb_enderecos_idenderecos' => $Endereco->getidenderecos(),
		'vltotal' => $total
	]);

	$Pedidos->salvarPedidos();

	header("Location: /order/".$Pedidos->getidperdidos());
	exit;

});

$app->post('/profile/change-password', function() { // os GETs são as rotas/caminhos que estão os Arquivos

	User::verificaLogin(false);

	if(!isset($_POST['current_pass']) || $_POST['current_pass'] === ''){
		User::setMsgErros("Digite a senha atual.");
		header("Location: /profile/change-password");
		exit;
	}

	if(!isset($_POST['new_pass']) || $_POST['new_pass'] === ''){
		User::setMsgErros("Digite a nova senha.");
		header("Location: /profile/change-password");
		exit;
	}

	if(!isset($_POST['new_pass_confirm']) || $_POST['new_pass_confirm'] === ''){
		User::setMsgErros("Digite a nova senha de confirmação.");
		header("Location: /profile/change-password");
		exit;
	}

	if($_POST['current_pass'] === $_POST['new_pass']){
		User::setMsgErros("A nova senha deve ser diferente da atual.");
		header("Location: /profile/change-password");
		exit;
	}
	if($_POST['new_pass'] !== $_POST['new_pass_confirm']){
		User::setMsgErros("A Nova Senha e Confirmar Senha devem ser iguais.");
		header("Location: /profile/change-password");
		exit;
	}
	$User = User::getGerandoSessionUsuario();

	if(!password_verify($_POST['current_pass'], $User->getdespassword())){
		User::setMsgErros("Senha Inválida.");
		header("Location: /profile/change-password");
		exit;
	}

	$User->setdespassword($_POST['new_pass']);
	$User->updateSalvar();
	User::setMsgSucessoDoRegistros("Senha Alterada com sucesso.");;
	header("Location: /profile/change-password");
	exit;
});