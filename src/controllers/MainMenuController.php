<?php namespace ratzslayer3\controllers;

use \Psr\Http\Message\ServerRequestInterface    as Request;
use \Psr\Http\Message\ResponseInterface         as Response;


class MainMenuController extends SuperController {

    public function get(Request $req, Response $res, array $args) {
        return $this->views->render($res, 'main-menu.html.twig', ['title' => 'RatzSlayer3', 'dir' =>  $this->dir, 'admin' => $_SESSION['admin']]);
    }

}
