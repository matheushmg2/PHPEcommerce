<?php 

session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;

$app = new Slim();

$app->config('debug', true);

require_once("Sites.php");
require_once("Adms.php");
require_once("AdmsUsuario.php");
require_once("AdmsCategoria.php");
require_once("AdmsProduto.php");
require_once("AdmsPedidos.php");

require_once("funcoes.php");

$app->run();

 ?>