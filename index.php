<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

use ratzslayer3\conf\ConnectionFactory  as CF;

$cf = new CF();
$cf->setConfig('src/conf/conf.ini');
$db = $cf->makeConnection();

//Get root path, parse and add it into container
$dir = realpath('./');
$dir = explode('/', $dir);
$container['dir'] = end($dir);

// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('src/views', [
        // 'cache' => 'path/to/cache'
    ]);

    // Instantiate and add Slim specific extension
    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    return $view;
};

/**
 * Rewrite start here
 */
$app = new \Slim\App($container);

// Get container
// $container = $app->getContainer();

$app->group('/characters', function ($app) {
    $app->get('',           "\\ratzslayer3\\controllers\\CharactersController:get");
    $app->get('/create',    "\\ratzslayer3\\controllers\\CharactersController:new");
    $app->post('/add',      "\\ratzslayer3\\controllers\\CharactersController:add");
});

$app->group('/monster', function ($app) {
    $app->get('',           "\\ratzslayer3\\controllers\\MonstersController:get");
    $app->get('/create',    "\\ratzslayer3\\controllers\\MonstersController:new");
});

/**
 * test
 */
$app->get('/', function (Request $req, Response $res, array $args) {
    $res->getBody()->write("Coffe is ready!");
    return $res;
});

$app->run();
