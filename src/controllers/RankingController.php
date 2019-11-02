<?php namespace ratzslayer3\controllers;

use \Psr\Http\Message\ServerRequestInterface    as Request;
use \Psr\Http\Message\ResponseInterface         as Response;
use ratzslayer3\models\Character                as CHR;
use ratzslayer3\models\Monster                  as MST;
use ratzslayer3\models\Fight                    as FGT;
use ratzslayer3\models\FightLog                 as FGL;

class RankingController extends SuperController {

    public function get(Request $req, Response $res, array $args) {
        $characters = CHR::all();
        $monsters = MST::all();
        //Get fighter fight's stats
        $winsC = array();
        $fightsC = array();
        $takeC = array();
        $giveC = array();
        $winsM = array();
        $fightsM = array();
        $takeM = array();
        $giveM = array();
        //Get stats for character
        foreach ($characters as $char) {
          $fights = FGT::where('id_characters', $char->id)->get();
          $win = FGT::where('id_characters', $char->id)->where('winner', 'c')->get();
          $taken = FGL::where('fighter_type', 'c')->where('id_fighter', $char->id)->get();
          $winsC[$char->id] = count($win);
          $fightsC[$char->id] = count($fights);
          //get all damage give
          foreach ($fights as $fight) {
            $give = FGL::where('id_fights', $fight->id)->where('fighter_type', 'm')->get();
            foreach ($give as $round) {
              $giveC[$char->id] += $round->damage;
            }
          }
          //get all damage take
          foreach ($taken as $round) {
            $takeC[$char->id] += $round->damage;
          }
        }
        //Get stats for Monster
        foreach ($monsters as $monster) {
          $fights = FGT::where('id_monsters', $monster->id)->get();
          $win = FGT::where('id_monsters', $monster->id)->where('winner', 'm')->get();
          $taken = FGL::where('fighter_type', 'm')->where('id_fighter', $monster->id)->get();
          $winsM[$monster->id] = count($win);
          $fightsM[$monster->id] = count($fights);
          //get all damage give
          foreach ($fights as $fight) {
            $give = FGL::where('id_fights', $fight->id)->where('fighter_type', 'c')->get();
            foreach ($give as $round) {
              $giveM[$monster->id] += $round->damage;
            }
          }
          //get all damage take
          foreach ($taken as $round) {
            $takeM[$monster->id] += $round->damage;
          }
        }

        return $this->views->render($res, 'ranking.html.twig', ['title' => 'Ranking','dir' => $this->dir,'winsC' => $winsC, 'winsM' => $winsM, 'monsters' => $monsters, 'characters' => $characters, 'fightsC' => $fightsC, 'fightsM' => $fightsM, 'takeM' => $takeM, 'giveM' => $giveM, 'takeC' => $takeC, 'giveC' => $giveC ]);
    }
}
