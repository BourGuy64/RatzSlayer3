<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

use Illuminate\Support\Facades\Auth;

use ratzslayer3\conf\ConnectionFactory  as CF;
use ratzslayer3\middlewares\GlobalMiddleware;
use ratzslayer3\middlewares\AuthentificationMiddleware;
use ratzslayer3\middlewares\ConnectedRejectMiddleware;

session_start();

// $_SESSION['admin'] = true; // DEV ( fake admin connection )
// $_SESSION['userId'] = 1; // DEV ( fake admin connection )

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
});
$app->group('/characters', function ($app) {
    $app->get('/create',        "\\ratzslayer3\\controllers\\CharactersController:createForm");
    $app->get('/edit/{id}',     "\\ratzslayer3\\controllers\\CharactersController:editForm");
    $app->post('',              "\\ratzslayer3\\controllers\\CharactersController:create");
    $app->post('/edit/{id}',    "\\ratzslayer3\\controllers\\CharactersController:update");
    $app->delete('/{id}',       "\\ratzslayer3\\controllers\\CharactersController:delete");
})->add( new AuthentificationMiddleware() );

// MONSTERS
$app->group('/monsters', function ($app) {
    $app->get('',               "\\ratzslayer3\\controllers\\MonstersController:get");
});
$app->group('/monsters', function ($app) {
    $app->get('/create',        "\\ratzslayer3\\controllers\\MonstersController:createForm");
    $app->get('/edit/{id}',     "\\ratzslayer3\\controllers\\MonstersController:editForm");
    $app->post('',              "\\ratzslayer3\\controllers\\MonstersController:create");
    $app->post('/edit/{id}',    "\\ratzslayer3\\controllers\\MonstersController:update");
    $app->delete('/{id}',       "\\ratzslayer3\\controllers\\MonstersController:delete");
})->add( new AuthentificationMiddleware() );

// FIGHT
$app->group('/fight', function ($app) {
    $app->get('',           "\\ratzslayer3\\controllers\\FightController:get");
    $app->post('',           "\\ratzslayer3\\controllers\\FightController:fight");
    $app->delete('',        "\\ratzslayer3\\controllers\\FightController:delete");
});

// FIGHT
$app->group('/fightlog', function ($app) {
    $app->get('/{id}',          "\\ratzslayer3\\controllers\\FightLogController:get");
    $app->get('/last/{fightId}/{fighterType}/{fighterId}', "\\ratzslayer3\\controllers\\FightLogController:getLast");
    $app->post('',              "\\ratzslayer3\\controllers\\FightLogController:nextRound");
});

// USERS
$app->group('/users', function ($app) {
    $app->get('/login',     "\\ratzslayer3\\controllers\\UsersController:loginForm")->add( new ConnectedRejectMiddleware() );
    $app->post('/login',    "\\ratzslayer3\\controllers\\UsersController:login");
    $app->get('/logout',    "\\ratzslayer3\\controllers\\UsersController:logout");
});
$app->group('/users', function ($app) {
    $app->get('/parameters',    "\\ratzslayer3\\controllers\\UsersController:parameters");
    $app->get('/create',        "\\ratzslayer3\\controllers\\UsersController:createForm");
    $app->post('',              "\\ratzslayer3\\controllers\\UsersController:create");
    $app->delete('/{id}',       "\\ratzslayer3\\controllers\\UsersController:delete");
})->add( new AuthentificationMiddleware() );

// RANKING
$app->group('/ranking', function ($app) {
    $app->get('',           "\\ratzslayer3\\controllers\\RankingController:get");
});

// MAIN MENU
$app->get('/',              "\\ratzslayer3\\controllers\\MainMenuController:get");

// MISC
// $app->get('/hashPassword/{password}', "\\ratzslayer3\\controllers\\UsersController:hashPassword"); // DEV

$app->run();
