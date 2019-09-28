<?php namespace ratzslayer3\controllers;

use \Psr\Http\Message\ServerRequestInterface    as Request;
use \Psr\Http\Message\ResponseInterface         as Response;
use ratzslayer3\models\Character                as CHR;


class CharactersController extends SuperController {

    public function get(Request $req, Response $res, array $args) {
        $characters = CHR::all();
        return $this->views->render($res, 'fighters.html.twig', ['title' => 'Monsters','dir' => $this->dir, 'fighters' => $characters]);
    }

    public function createForm(Request $req, Response $res, array $args) {
        return $this->views->render($res, 'form-char.html.twig', ['title' => 'New character', 'dir' => $this->dir]);
    }

    public function create(Request $req, Response $res, array $args) {
        $char = CHR::where('lastname', 'like', $_POST['lastname'])
            ->where('firstname', 'like', $_POST['firstname'])
            ->first();
        if ($char) {
            return $res->withStatus(400);
        }

        // image upload
        $fileInfo = pathinfo($_FILES['img']['name']);
        $extension = $fileInfo['extension']; // get the extension of the file
        $fileName = $_POST['firstname'] . $_POST['lastname'] . '.' . $extension;
        $target = 'src/img/fighters/' . $fileName; // better link maybe ?
        move_uploaded_file($_FILES['img']['tmp_name'], $target);

        $char = new CHR;
        $char->lastname     = $_POST['lastname'];
        $char->firstname    = $_POST['firstname'];
        $char->weight       = $_POST['weight'];
        $char->size         = $_POST['size'];
        $char->hp           = $_POST['hp'];
        $char->attack       = $_POST['attack'];
        $char->def          = $_POST['def'];
        $char->agility      = $_POST['agility'];
        $char->picture      = $fileName;
        $char->save();

        return $res->withJson($char);
    }
}
