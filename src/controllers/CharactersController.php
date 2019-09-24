<?php namespace ratzslayer3\controllers;

use \Psr\Http\Message\ServerRequestInterface    as Request;
use \Psr\Http\Message\ResponseInterface         as Response;
use ratzslayer3\models\Character                as CHR;


class CharactersController extends SuperController {

    public function get(Request $req, Response $res, array $args) {
        $characters = CHR::all();
        var_dump($characters);
    }

    public function createForm(Request $req, Response $res, array $args) {
        return $this->views->render($res, 'form-char.html.twig', ['title' => 'New character', 'dir' =>  $this->dir]);
    }

    public function create(Request $req, Response $res, array $args) {
        $body = json_decode($req->getBody());

        $char = CHR::where('lastname', 'like', $body->lastname)
            ->where('firstname', 'like', $body->firstname)
            ->first();
        if ($char) {
            return $res->withStatus(400);
        }

        $char = new CHR;
        $char->lastname     = $body->lastname;
        $char->firstname    = $body->firstname;
        $char->weight       = $body->weight;
        $char->size         = $body->size;
        $char->hp           = $body->hp;
        $char->attack       = $body->attack;
        $char->def          = $body->def;
        $char->agility      = $body->agility;
        $char->save();

        return $res->withJson($body);
    }
}
