<?php namespace ratzslayer3\controllers;

use \Psr\Http\Message\ServerRequestInterface    as Request;
use \Psr\Http\Message\ResponseInterface         as Response;
use ratzslayer3\models\Character                as CHR;


class CharactersController extends SuperController {

    public function get(Request $req, Response $res, array $args) {
        $characters = CHR::all();
        var_dump($characters);
    }

    public function new(Request $req, Response $res, array $args) {
        return $this->views->render($res, 'form-char.html.twig', ['title' => 'New character', 'dir' =>  $this->c['dir']]);
    }
}
