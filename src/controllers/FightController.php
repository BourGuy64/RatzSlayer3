<?php namespace ratzslayer3\controllers;

use \Psr\Http\Message\ServerRequestInterface    as Request;
use \Psr\Http\Message\ResponseInterface         as Response;
use ratzslayer3\models\Character                as CHR;
use ratzslayer3\models\Monster                  as MST;
use ratzslayer3\models\FightLog                 as FGL;
use ratzslayer3\models\Fight                    as FGT;

class FightController extends SuperController{
  public function get(Request $req, Response $res, array $args) {
      $characters = CHR::all();
      $monsters = MST::all();
      return $this->views->render($res, 'fight.html.twig', ['title' => 'Choose your fighter', 'dir' =>  $this->dir, 'characters' => $characters, 'monsters' => $monsters, 'admin' => $_SESSION['admin']]);
  }

  //Do whole fight
  public function fight(Request $req, Response $res, array $args){
    $character = CHR::where('id', $_POST['char'])->first();
    $monster = MST::where('id', $_POST['monster'])->first();
    if(!$monster && !$character){
      echo 'PAS BON';
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
      $this->roundZero($monster, $character, $fight->id);
      while(!$this->fighterIsDying($fight->id)){
        $this->doAttack($monster, $character, $fight->id, $round);
        if(!$this->fighterIsDying($fight->id)){
          $this->doAttack($character, $monster, $fight->id, $round);
        }
        $round++;
      }
    }
    //If CHARACTER have more agility, he attack first
    else{
      $this->roundZero($character, $monster, $fight->id);
      while(!$this->fighterIsDying($fight->id)){
        $this->doAttack($character, $monster, $fight->id, $round);
        if(!$this->fighterIsDying($fight->id)){
          $this->doAttack($monster, $character, $fight->id, $round);
        }
        $round++;
      }
    }
    $winner = $this->winner($fight->id, $round);
    $fight->winner = $winner;
    $fight->save();
    $logChar = FGL::where('id_fights', $fight->id)->where('fighter_type', 'c')->orderBy('id', 'asc')->get();
    $logMonster = FGL::where('id_fights', $fight->id)->where('fighter_type', 'm')->orderBy('id', 'asc')->get();
    return $this->views->render($res, 'fighting.html.twig', ['title' => 'Fight !', 'dir' =>  $this->dir, 'character' => $character, 'monster' => $monster, 'logChar' => $logChar, 'logMonster' => $logMonster, 'winner' => $winner, 'admin' => $_SESSION['admin']]);
  }

  public function doAttack($fighter, $target, $fightId, $round){
    //Create new fight log
    $fightlog = new FGL;
    $fightlog->id_fights = $fightId;
    $fightlog->id_fighter = $target->id;
    $fightlog->round = $round;
    $fightlog->fighter_type = $target->type;
    //Get hp of target from last round
    $lastRound = FGL::where('round', $round-1)->where('id_fighter', $target->id)->where('fighter_type', $target->type)->orderBy('id', 'desc')->first();
    $leftLife = $lastRound->hp - $fighter->attack;
    if($leftLife < 0){
      $leftLife = 0;
    }
    $fightlog->hp = $leftLife;
    $fightlog->save();
  }

  //Init fight log, add life for fighters
  public function roundZero($firstFighter, $secondFighter, $fightId){
    //Init first fighter log
    $fightlog = new FGL;
    $fightlog->id_fights = $fightId;
    $fightlog->id_fighter = $firstFighter->id;
    $fightlog->round = 0;
    $fightlog->hp = $firstFighter->hp;
    if($firstFighter->type == 'c'){
      $fightlog->fighter_type = 'c';
    }
    else if($firstFighter->type == 'm'){
      $fightlog->fighter_type = 'm';
    }
    $fightlog->save();

    //Second fighter, with lower agility
    $fightlog = new FGL;
    $fightlog->id_fights = $fightId;
    $fightlog->id_fighter = $secondFighter->id;
    $fightlog->round = 0;
    $fightlog->hp = $secondFighter->hp;
    if($secondFighter->type == 'c'){
      $fightlog->fighter_type = 'c';
    }
    else if($secondFighter->type == 'm'){
      $fightlog->fighter_type = 'm';
    }
    $fightlog->save();
  }

  //Verify if a fighter is dead (use as condition in fight for interrupt it if a player with 0 hp in the fight)
  public function fighterIsDying($fightId){
    return FGL::where('id_fights', $fightId)->where('hp', '0')->first();
  }

  //Return fighter type of the winner
  public function winner($fightId, $round){
    $winner = FGL::where('id_fights', $fightId)->where('hp', '>', '0')->orderBy('id', 'desc')->first();
    return $winner->fighter_type;
  }
}
