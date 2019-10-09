<?php namespace ratzslayer3\controllers;

use \Psr\Http\Message\ServerRequestInterface    as Request;
use \Psr\Http\Message\ResponseInterface         as Response;
use ratzslayer3\models\Character                as CHR;
use ratzslayer3\tools\ImageTools;


class CharactersController extends SuperController {

    public function get(Request $req, Response $res, array $args) {
        $characters = CHR::all();
        return $this->views->render($res, 'fighters.html.twig', ['title' => 'Characters','dir' => $this->dir, 'fighters' => $characters, 'fighterType' => 'characters', 'admin' => $_SESSION['admin']]);
    }

    public function createForm(Request $req, Response $res, array $args) {
        return $this->views->render($res, 'form-char.html.twig', ['title' => 'New character', 'dir' => $this->dir, 'admin' => $_SESSION['admin']]);
    }

    public function editForm(Request $req, Response $res, array $args) {
        $character = CHR::find($args['id']);
        return $this->views->render($res, 'edit-character.html.twig', ['title' => 'Edit Character', 'dir' => $this->dir, 'fighter' => $character, 'admin' => $_SESSION['admin']]);
    }

    public function create(Request $req, Response $res, array $args) {

        // test if character is unique
        $char = CHR::where('lastname', 'like', $_POST['lastname'])
            ->where('firstname', 'like', $_POST['firstname'])
            ->first();
        if ($char) {
            return $res->withStatus(400);
        }

        // upload image
        $image = new ImageTools($_FILES['img'], $_POST['firstname'] . $_POST['lastname']);
        $image->upload();

        $char = new CHR;
        $char->type      = 'c';
        $char->lastname     = $_POST['lastname'];
        $char->firstname    = $_POST['firstname'];
        $char->weight       = $_POST['weight'];
        $char->size         = $_POST['size'];
        $char->hp           = $_POST['hp'];
        $char->attack       = $_POST['attack'];
        $char->def          = $_POST['def'];
        $char->agility      = $_POST['agility'];
        $char->picture      = $image->getFileName();
        $char->save();

        return $res->withJson($char);
    }

    public function update(Request $req, Response $res, array $args) {


        // test if character is unique
        $character = CHR::find($args['id']);

        // upload image
        if($_FILES['img'])
        {
          $image = new ImageTools($_FILES['img'], $_POST['name']);
          $image->upload();
          $monster->picture   = $image->getFileName();
        }

        $character->lastname      = $_POST['lastname'];
        $character->firstname      = $_POST['firstname'];
        $character->weight    = $_POST['weight'];
        $character->size      = $_POST['size'];
        $character->hp        = $_POST['hp'];
        $character->attack    = $_POST['attack'];
        $character->def       = $_POST['def'];
        $character->agility   = $_POST['agility'];

        $character->save();

        return $res->withJson($character);
    }

    public function delete(Request $req, Response $res, array $args) {
        $character = CHR::find($args['id']);
        $character->delete();
        return $res->withStatus(200);
    }

}
