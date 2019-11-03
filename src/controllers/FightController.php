<?php namespace ratzslayer3\controllers;

use \Psr\Http\Message\ServerRequestInterface    as Request;
use \Psr\Http\Message\ResponseInterface         as Response;
use ratzslayer3\models\Character                as CHR;
use ratzslayer3\models\Monster                  as MST;
use ratzslayer3\models\FightLog                 as FGL;
use ratzslayer3\models\Fight                    as FGT;
use ratzslayer3\controllers\FightLogController  as FGLC;


class FightController extends SuperController{

    public function get(Request $req, Response $res, array $args) {
        if (isset($_COOKIE['CurrentFight']) && $_COOKIE['CurrentFight']) {
            $fightId = $_COOKIE['CurrentFight'];

            $fight = FGT::where('id', $fightId)->first();
            $character = CHR::where('id', $fight->id_characters)->first();
            $monster = MST::where('id', $fight->id_monsters)->first();

            $logChar = FGL::where('id_fights', $fightId)
                ->where('fighter_type', 'c')
                ->orderBy('id', 'desc')
                ->get();

            $logMonster = FGL::where('id_fights', $fightId)
                ->where('fighter_type', 'm')
                ->orderBy('id', 'desc')
                ->get();

            $winner = $this->winner($logChar[0], $logMonster[0]);

            //Get all damage sudden by monster
            foreach ($logMonster as $log) {
              $monsterDamage += $log->damage;
            }
            //Get all damage sudden by character
            foreach ($logChar as $log) {
              $charDamage += $log->damage;
            }
            return $this->views->render($res, 'fighting.html.twig', ['title' => 'Fight !', 'dir' =>  $this->dir, 'character' => $character, 'monster' => $monster, 'logChar' => $logChar, 'logMonster' => $logMonster, 'winner' => $winner, 'charDamage' => $charDamage, 'monsterDamage' => $monsterDamage, 'admin' => $_SESSION['admin']]);
        } else {
            $characters = CHR::all();
            $monsters = MST::all();
            return $this->views->render($res, 'fight.html.twig', ['title' => 'Choose your fighter', 'dir' =>  $this->dir, 'characters' => $characters, 'monsters' => $monsters, 'admin' => $_SESSION['admin']]);
        }
    }

  //Do whole fight
  public function fight(Request $req, Response $res, array $args) {
      $character = CHR::find($_POST['char']);
      $monster = MST::find($_POST['monster']);

      if(!($monster && $character)){
          return $res->withStatus(400);
      }

      $fight = new FGT;
      $fight->id_characters = $character->id;
      $fight->id_monsters = $monster->id;
      $fight->winner = 0;
      $fight->save();

      if ($monster->agility > $character->agility) {
          FGLC::roundZero($monster, $character, $fight->id);
      } else {
          FGLC::roundZero($character, $monster, $fight->id);
      }

      setcookie("CurrentFight", $fight->id, time() + (10 * 365 * 24 * 60 * 60));
      setcookie("fight", $fight->id, time() + (10 * 365 * 24 * 60 * 60));

      return $this->views->render($res, 'fighting.html.twig', ['title' => 'Fight !', 'dir' =>  $this->dir, 'character' => $character, 'monster' => $monster, 'admin' => $_SESSION['admin']]);
  }

  //Return fighter type of the winner
    public function winner($character, $monster) {
        if ($character->hp == 0) {
            return 'm';
        } else if ($monster->hp == 0) {
            return 'c';
        }

        return false;
    }

    public function delete(Request $req, Response $res, array $args){
      $fightId = $_COOKIE['CurrentFight'];
      setcookie('CurrentFight', time() - 3600);
      FGL::where('id_fights', $fightId)->delete();
      FGT::where('id', $fightId)->delete();
    }
}
