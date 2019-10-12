<?php namespace ratzslayer3\controllers;

use \Psr\Http\Message\ServerRequestInterface    as Request;
use \Psr\Http\Message\ResponseInterface         as Response;
use ratzslayer3\models\Character                as CHR;
use ratzslayer3\models\Monster                  as MST;
use ratzslayer3\models\FightLog                 as FGL;
use ratzslayer3\models\Fight                    as FGT;

class FightLogController extends SuperController{

  public static function doAttack($fighter, $target, $fightId, $round){
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
  public static function roundZero($firstFighter, $secondFighter, $fightId){
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
  public static function fighterIsDying($fightId){
    return FGL::where('id_fights', $fightId)->where('hp', '0')->first();
  }

}
