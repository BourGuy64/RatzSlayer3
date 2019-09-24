<?php namespace ratzslayer3\controller;

use \Psr\Http\Message\ServerRequestInterface    as Request;
use \Psr\Http\Message\ResponseInterface         as Response;
use ratzslayer3\models\Character                as CHR;


class Character extends SuperController {

    public function get(Request $req, Response $res, array $args) {
        $characters = CHR::all();
    }
}
