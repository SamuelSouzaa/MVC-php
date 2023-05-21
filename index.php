<?php

require __DIR__ . '/vendor/autoload.php';

use \App\Http\Router;
use \App\Http\Response;
use \App\Controller\Pages\Home;
use \App\Utils\View;

define('URL','http://localhost/MVC');

//Define o valor padrão das variáveis
View::init([
    'URL' => URL
]);

//Inicia o Router
$obRouter = new Router(URL);

//Inclui as rotas de páginas
include __DIR__.'/routes/pages.php';

//Imprime o response da rota
$obRouter->run()->sendResponse();


?>
