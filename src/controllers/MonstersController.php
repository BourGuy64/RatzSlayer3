<?php namespace ratzslayer3\controllers;

use \Psr\Http\Message\ServerRequestInterface    as Request;
use \Psr\Http\Message\ResponseInterface         as Response;
use ratzslayer3\models\Monster                  as MST;
use ratzslayer3\models\Fight                    as FGT;
use ratzslayer3\tools\ImageTools;

class MonstersController extends SuperController {

    public function get(Request $req, Response $res, array $args) {
        $monsters = MST::all();
        //Get fighter fight's stats
        $winners = array();
        $fights = array();
        foreach ($monsters as $monster) {
          $fight = FGT::where('id_monsters', $monster->id)->get();
          $win = FGT::where('id_monsters', $monster->id)->where('winner', 'm')->get();
          $winners[$monster->id] = count($win);
          $fights[$monster->id] = count($fight);
        }
        return $this->views->render($res, 'fighters.html.twig', ['title' => 'Monsters','dir' => $this->dir, 'fighters' => $monsters, 'fighterType' => 'monsters', 'winners' => $winners, 'fights' => $fights, 'admin' => $_SESSION['admin']]);
    }

    public function createForm(Request $req, Response $res, array $args) {
        return $this->views->render($res, 'form-monster.html.twig', ['title' => 'New monster', 'dir' => $this->dir, 'admin' => $_SESSION['admin']]);
    }

    public function editForm(Request $req, Response $res, array $args) {
        $monster = MST::find($args['id']);
        return $this->views->render($res, 'edit-monster.html.twig', ['title' => 'Edit Monster', 'dir' => $this->dir, 'fighter' => $monster, 'admin' => $_SESSION['admin']]);
    }

    public function create(Request $req, Response $res, array $args) {

        // test if character is unique
        $monster = MST::where('name', 'like', $_POST['name'])->first();
        if ($monster) {
            return $res->withJson([
              "error_code" => 1,
              "message" => "Erreur, nom du monstre déjà pris"
            ]);
        }

        $image = new ImageTools($_FILES['img'], $_POST['name']);
        $image->upload();

        $monster = new MST;
        $monster->type      = 'm';
        $monster->name      = $_POST['name'];
        $monster->weight    = $_POST['weight'];
        $monster->size      = $_POST['size'];
        $monster->hp        = $_POST['hp'];
        $monster->attack    = $_POST['attack'];
        $monster->def       = $_POST['def'];
        $monster->agility   = $_POST['agility'];
        $monster->picture   = $image->getFileName();
        $monster->save();

        return $res->withJson([
          "error_code" => 0,
          "message" => "Monster créé"
        ]);
    }

    public function delete(Request $req, Response $res, array $args) {
        $monster = MST::find($args['id']);
        $monster->delete();
        return $res->withStatus(200);
    }

    public function update(Request $req, Response $res, array $args) {

      $existMonster = MST::where('name', 'like', $_POST['name'])->first();
      // test if character is unique
      $monster = MST::find($args['id']);

      //test to allow keeping the same monster name
      if($monster->name != $_POST['name'])
      {
        if ($existMonster)
        {
            return $res->withJson([
              "error_code" => 1,
              "message" => "Erreur, nom du monstre déjà pris"
            ]);
        }
      }

        // upload image
        if(isset($_FILES) && isset($_FILES['img']) && $_FILES['img'] )
        {
          $image = new ImageTools($_FILES['img'], $_POST['name']);
          $image->upload();
          $monster->picture   = $image->getFileName();
        }


        $monster->name      = $_POST['name'];
        $monster->weight    = $_POST['weight'];
        $monster->size      = $_POST['size'];
        $monster->hp        = $_POST['hp'];
        $monster->attack    = $_POST['attack'];
        $monster->def       = $_POST['def'];
        $monster->agility   = $_POST['agility'];

        $monster->save();

        return $res->withJson([
          "error_code" => 0,
          "message" => "Monstre mis à jour"
        ]);


    }
}
