<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

use Illuminate\Support\Facades\Auth;

use ratzslayer3\conf\ConnectionFactory  as CF;
use ratzslayer3\middlewares\GlobalMiddleware;
use ratzslayer3\middlewares\AuthentificationMiddleware;

session_start();

$_SESSION['admin'] = true; // DEV ( fake admin connection )

$cf = new CF();
$cf->setConfig('src/conf/conf.ini');
$db = $cf->makeConnection();

//Get root path, parse and add it into container
$dir = str_replace('/index.php', '', $_SERVER['PHP_SELF']);
$container['dir'] = $dir;
$_SESSION['dir'] = $dir;

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
    $app->get('/create',    "\\ratzslayer3\\controllers\\CharactersController:createForm")->add( new AuthentificationMiddleware() );
    $app->post('',          "\\ratzslayer3\\controllers\\CharactersController:create")->add( new AuthentificationMiddleware() );
    $app->delete('/{id}',   "\\ratzslayer3\\controllers\\CharactersController:delete")->add( new AuthentificationMiddleware() );
});

// MONSTERS
$app->group('/monsters', function ($app) {
    $app->get('',               "\\ratzslayer3\\controllers\\MonstersController:get");
    $app->get('/create',        "\\ratzslayer3\\controllers\\MonstersController:createForm")->add( new AuthentificationMiddleware() );
    $app->get('/edit/{id}',     "\\ratzslayer3\\controllers\\MonstersController:editForm")->add( new AuthentificationMiddleware() );
    $app->post('',              "\\ratzslayer3\\controllers\\MonstersController:create")->add( new AuthentificationMiddleware() );
    $app->post('/edit/{id}',    "\\ratzslayer3\\controllers\\MonstersController:update")->add( new AuthentificationMiddleware() );
    $app->delete('/{id}',       "\\ratzslayer3\\controllers\\MonstersController:delete")->add( new AuthentificationMiddleware() );
});

// FIGHT
$app->group('/fight', function ($app) {
    $app->get('',           "\\ratzslayer3\\controllers\\FightController:get");
});

// USERS
$app->group('/users', function ($app) {
    $app->get('/login',     "\\ratzslayer3\\controllers\\UsersController:loginForm");
    $app->post('/login',    "\\ratzslayer3\\controllers\\UsersController:login");
    $app->get('/logout',    "\\ratzslayer3\\controllers\\UsersController:logout");
});

// MAIN MENU
$app->get('/',              "\\ratzslayer3\\controllers\\MainMenuController:get");

$app->run();
