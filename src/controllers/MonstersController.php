<?php namespace ratzslayer3\controllers;

use \Psr\Http\Message\ServerRequestInterface    as Request;
use \Psr\Http\Message\ResponseInterface         as Response;
use ratzslayer3\models\Monster                  as MST;
use ratzslayer3\tools\ImageTools;


class MonstersController extends SuperController {

    public function get(Request $req, Response $res, array $args) {
        $monsters = MST::all();
        return $this->views->render($res, 'fighters.html.twig', ['title' => 'Monsters','dir' => $this->dir, 'fighters' => $monsters]);
    }

    public function createForm(Request $req, Response $res, array $args) {
        return $this->views->render($res, 'form-monster.html.twig', ['title' => 'New character', 'dir' => $this->dir]);
    }

    public function create(Request $req, Response $res, array $args) {

        // test if character is unique
        $monster = MST::where('name', 'like', $_POST['name'])->first();
        if ($monster) {
            return $res->withStatus(400);
        }

        // upload image
        $image = new ImageTools($_FILES['img'], $_POST['name']);
        $image->upload();

        $monster = new MST;
        $monster->name      = $_POST['name'];
        $monster->weight    = $_POST['weight'];
        $monster->size      = $_POST['size'];
        $monster->hp        = $_POST['hp'];
        $monster->attack    = $_POST['attack'];
        $monster->def       = $_POST['def'];
        $monster->agility   = $_POST['agility'];
        $monster->picture   = $image->getFileName();
        $monster->save();

        return $res->withJson($monster);
    }

}
