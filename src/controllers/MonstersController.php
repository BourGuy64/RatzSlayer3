<?php namespace ratzslayer3\controllers;

use \Psr\Http\Message\ServerRequestInterface    as Request;
use \Psr\Http\Message\ResponseInterface         as Response;
use ratzslayer3\models\Monster                  as MST;


class MonstersController extends SuperController {

    public function get(Request $req, Response $res, array $args) {
        $monsters = MST::all();
        var_dump($monsters);
    }

    public function createForm(Request $req, Response $res, array $args) {
        return $this->views->render($res, 'form-monster.html.twig', ['title' => 'New character', 'dir' =>  $this->dir]);
    }
}
