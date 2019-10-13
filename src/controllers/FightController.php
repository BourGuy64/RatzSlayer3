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
      if(isset($_COOKIE['CurrentFight'])){
        $fightId = $_COOKIE['CurrentFight'];
        $fight = FGT::where('id', $fightId)->first();
        $character = CHR::where('id', $fight->id_characters)->first();
        $monster = MST::where('id', $fight->id_monsters)->first();
        $logChar = FGL::where('id_fights', $fightId)->where('fighter_type', 'c')->orderBy('id', 'asc')->get();
        $logMonster = FGL::where('id_fights', $fightId)->where('fighter_type', 'm')->orderBy('id', 'asc')->get();
        $winner = $this->winner($fightId);
        return $this->views->render($res, 'fighting.html.twig', ['title' => 'Fight !', 'dir' =>  $this->dir, 'character' => $character, 'monster' => $monster, 'logChar' => $logChar, 'logMonster' => $logMonster, 'winner' => $winner, 'admin' => $_SESSION['admin']]);
      }
      else{
        $characters = CHR::all();
        $monsters = MST::all();
        return $this->views->render($res, 'fight.html.twig', ['title' => 'Choose your fighter', 'dir' =>  $this->dir, 'characters' => $characters, 'monsters' => $monsters, 'admin' => $_SESSION['admin']]);
      }
  }

  //Do whole fight
  public function fight(Request $req, Response $res, array $args){
    $character = CHR::where('id', $_POST['char'])->first();
    $monster = MST::where('id', $_POST['monster'])->first();
    if(!($monster && $character)){
      return $res->withStatus(400);
    }
    $fight = new FGT;
    $fight->id_characters = $character->id;
    $fight->id_monsters = $monster->id;
    $fight->winner = 0;
    $fight->save();
    $round = 1;
    //Fighter with more agility attack first (here is the MONSTER)
    if($monster->agility > $character->agility){
      FGLC::roundZero($monster, $character, $fight->id);
      while(!FGLC::fighterIsDying($fight->id)){
        FGLC::doAttack($monster, $character, $fight->id, $round);
        if(!FGLC::fighterIsDying($fight->id)){
          FGLC::doAttack($character, $monster, $fight->id, $round);
        }
        $round++;
      }
    }
    //If CHARACTER have more agility, he attack first
    else{
      FGLC::roundZero($character, $monster, $fight->id);
      while(!FGLC::fighterIsDying($fight->id)){
        FGLC::doAttack($character, $monster, $fight->id, $round);
        if(!FGLC::fighterIsDying($fight->id)){
          FGLC::doAttack($monster, $character, $fight->id, $round);
        }
        $round++;
      }
    }
    $winner = $this->winner($fight->id);
    $fight->winner = $winner;
    $fight->save();
    $logChar = FGL::where('id_fights', $fight->id)->where('fighter_type', 'c')->orderBy('id', 'asc')->get();
    $logMonster = FGL::where('id_fights', $fight->id)->where('fighter_type', 'm')->orderBy('id', 'asc')->get();
    setcookie("CurrentFight", $fight->id, time() + (10 * 365 * 24 * 60 * 60));
    return $this->views->render($res, 'fighting.html.twig', ['title' => 'Fight !', 'dir' =>  $this->dir, 'character' => $character, 'monster' => $monster, 'logChar' => $logChar, 'logMonster' => $logMonster, 'winner' => $winner, 'admin' => $_SESSION['admin']]);
  }

  //Return fighter type of the winner
  public function winner($fightId){
    $winner = FGL::where('id_fights', $fightId)->where('hp', '>', '0')->orderBy('id', 'desc')->first();
    return $winner->fighter_type;
  }
}
