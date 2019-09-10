<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

/**
 * Rewrite start here
 */
$app = new \Slim\App;

/**
 * test
 */
$app->get('/', function (Request $req, Response $res, array $args) {
    $res->getBody()->write("Coffe is ready!");
    return $res;
});

$app->run();
