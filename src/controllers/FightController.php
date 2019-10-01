<?php namespace ratzslayer3\controllers;

use \Psr\Http\Message\ServerRequestInterface    as Request;
use \Psr\Http\Message\ResponseInterface         as Response;
use ratzslayer3\models\Character                as CHR;
use ratzslayer3\models\Monster                  as MST;

class FightController extends SuperController{
  public function get(Request $req, Response $res, array $args) {
      $characters = chr::all();
      $monsters = MST::all();
      return $this->views->render($res, 'fight.html.twig', ['title' => 'Fight !', 'dir' =>  $this->dir, 'characters' => $characters, 'monsters' => $monsters]);
  }
}
