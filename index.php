<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

use ratzslayer3\conf\ConnectionFactory  as CF;
use ratzslayer3\middlewares\GlobalMiddleware;

$cf = new CF();
$cf->setConfig('src/conf/conf.ini');
$db = $cf->makeConnection();

//Get root path, parse and add it into container
$container['dir'] = str_replace('/index.php', '', $_SERVER['PHP_SELF']);

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

$container = $app->getContainer(); // get container

$app->add( new GlobalMiddleware() );

// CHARACTERS
$app->group('/characters', function ($app) {
    $app->get('',           "\\ratzslayer3\\controllers\\CharactersController:get");
    $app->get('/create',    "\\ratzslayer3\\controllers\\CharactersController:createForm");
    $app->post('',          "\\ratzslayer3\\controllers\\CharactersController:create");
    $app->delete('/{id}',   "\\ratzslayer3\\controllers\\CharactersController:delete");
});

// MONSTERS
$app->group('/monsters', function ($app) {
    $app->get('',           "\\ratzslayer3\\controllers\\MonstersController:get");
    $app->get('/create',    "\\ratzslayer3\\controllers\\MonstersController:createForm");
    $app->get('/edit/{id}', "\\ratzslayer3\\controllers\\MonstersController:editForm");
    $app->post('',          "\\ratzslayer3\\controllers\\MonstersController:create");
    $app->delete('/{id}',   "\\ratzslayer3\\controllers\\MonstersController:delete");
    $app->post('/edit/{id}',      "\\ratzslayer3\\controllers\\MonstersController:update");
});

// FIGHT
$app->group('/fight', function ($app) {
    $app->get('',           "\\ratzslayer3\\controllers\\FightController:get");
});

// MAIN MENU
$app->get('/',              "\\ratzslayer3\\controllers\\MainMenuController:get");

$app->run();
