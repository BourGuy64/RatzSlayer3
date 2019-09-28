<?php namespace ratzslayer3\controllers;

use \Psr\Http\Message\ServerRequestInterface    as Request;
use \Psr\Http\Message\ResponseInterface         as Response;
use ratzslayer3\models\Monster                  as MST;


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

        // image upload
        $fileInfo = pathinfo($_FILES['img']['name']);
        $extension = $fileInfo['extension']; // get the extension of the file
        $fileName = $_POST['name'] . '.' . $extension;
        $target = 'src/img/fighters/' . $fileName; // better link maybe ?
        move_uploaded_file($_FILES['img']['tmp_name'], $target);

        $monster = new MST;
        $monster->name      = $_POST['name'];
        $monster->weight    = $_POST['weight'];
        $monster->size      = $_POST['size'];
        $monster->hp        = $_POST['hp'];
        $monster->attack    = $_POST['attack'];
        $monster->def       = $_POST['def'];
        $monster->agility   = $_POST['agility'];
        $monster->picture   = $fileName;
        $monster->save();

        return $res->withJson($monster);
    }

}
