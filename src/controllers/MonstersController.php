<?php namespace ratzslayer3\controllers;

use \Psr\Http\Message\ServerRequestInterface    as Request;
use \Psr\Http\Message\ResponseInterface         as Response;
use ratzslayer3\models\Monster                  as MST;


class MonstersController extends SuperController {

    public function get(Request $req, Response $res, array $args) {
        $monsters = MST::all();
        return $this->views->render($res, 'fighters.html.twig', ['title' => 'Monsters','dir' =>  $this->dir, 'fighters' => $monsters]);
    }

    public function createForm(Request $req, Response $res, array $args) {
        return $this->views->render($res, 'form-monster.html.twig', ['title' => 'New character', 'dir' =>  $this->dir]);
    }

    public function create(Request $req, Response $res, array $args) {
        $body = json_decode($req->getBody());

        $monster = MST::where('name', 'like', $body->name)->first();
        if ($monster) {
            return $res->withStatus(400);
        }

        $monster = new MST;
        $monster->name      = $body->name;
        $monster->weight    = $body->weight;
        $monster->size      = $body->size;
        $monster->hp        = $body->hp;
        $monster->attack    = $body->attack;
        $monster->def       = $body->def;
        $monster->agility   = $body->agility;
        $monster->save();

        return $res->withJson($body);
    }
}
