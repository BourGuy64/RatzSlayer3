<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

use ratzslayer3\conf\ConnectionFactory  as CF;


$cf = new CF();
$cf->setConfig('src/conf/conf.ini');
$db = $cf->makeConnection();

/**
 * Rewrite start here
 */
$app = new \Slim\App;


// Get container
$container = $app->getContainer();

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

$app->get('/twig', function ($request, $response, $args) {
    return $this->view->render($response, 'body.html.twig', ['title' => 'Twig test']);
});

$app->get('/add-char', function ($request, $response, $args) {
    return $this->view->render($response, 'form-char.html.twig', ['title' => 'Creation d\'un personnage']);
});

/**
 * test
 */
$app->get('/cake', function (Request $req, Response $res, array $args) {
    $res->getBody()->write("Cake is a lie");
    return $res;
 });

$app->get('/', function (Request $req, Response $res, array $args) {
    $res->getBody()->write("Coffe is ready!");
    return $res;
});

$app->group('/characters', function ($app) {
    $app->get('', "\\ratzslayer3\\controller\\Character:get");
});

$app->run();
