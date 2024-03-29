<?php namespace ratzslayer3\controllers;

use \Psr\Http\Message\ServerRequestInterface    as Request;
use \Psr\Http\Message\ResponseInterface         as Response;
use ratzslayer3\models\Character                as CHR;
use ratzslayer3\models\Monster                  as MST;
use ratzslayer3\models\FightLog                 as FGL;
use ratzslayer3\models\Fight                    as FGT;

class FightLogController extends SuperController{

    public function get(Request $req, Response $res, array $args) {
        $logs = FGL::find($args['id']);
    }

    public static function getRound($fight, $round) {
        $lastRound = FGL::where('id_fights', $fight)
            ->where('round', $round)
            ->get();

        return $lastRound;
    }

    public function getLast(Request $req, Response $res, array $args) {

        $fight = FGT::find($args['fightId']);
        if ($args['fighterType'] == 'c') {
            $fighter = CHR::find($args['fighterId']);
        } else if ($args['fighterType'] == 'm') {
            $fighter = MST::find($args['fighterId']);
        }

        if (!$fighter) {
            return $res->withStatus(400);
        }

        $log = FGL::orderBy('round', 'desc')
            ->where('fighter_type', $args['fighterType'])
            ->where('id_fights', $fight->id)
            ->first();

        return $this->views->render($res, 'fightlog.html.twig', ['dir' =>  $this->dir, 'fighter' => $fighter, 'log' => $log ]);
    }

    public function nextRound(Request $req, Response $res, array $args) {

        $fight = FGT::find($_POST['fightId']);
        $character = CHR::find($_POST['char']);
        $monster = MST::find($_POST['monster']);
        if(!($monster && $character)){
            return $res->withStatus(400);
        }

        $lastRound = FGL::orderBy('round', 'desc')
            ->where('id_fights', $fight->id)
            ->first(); // gets the whole row
        $currentRound = $lastRound->round + 1;


        if ($monster->agility > $character->agility) {
            $this->doAttack($character, $monster, $fight->id, $currentRound);
            if (!$this->fighterIsDying($fight->id)) {
                $this->doAttack($monster, $character, $fight->id, $currentRound);
            }
        } else {
            $this->doAttack($monster, $character, $fight->id, $currentRound);
            if (!$this->fighterIsDying($fight->id)) {
                $this->doAttack($character, $monster, $fight->id, $currentRound);
            }
        }

        // return $res->withJson(SELF::getRound($fight->id, $currentRound));

        $logChar = FGL::where('id_fights', $fight->id)
            ->where('fighter_type', 'c')
            ->orderBy('id', 'desc')
            ->first();

        $logMonster = FGL::where('id_fights', $fight->id)
            ->where('fighter_type', 'm')
            ->orderBy('id', 'desc')
            ->first();

        $value = 0;
        if ($logChar->hp <= 0) {
            $value = 'm';
            $fight->winner = $value;
            $fight->save();
        } else if ($logMonster->hp <= 0) {
            $value = 'c';
            $fight->winner = $value;
            $fight->save();
        }

        // return $res->withJson($logChar);
        return $res->getBody()->write($value);

    }

  public static function doAttack($fighter, $target, $fightId, $round){
    $realAttack = SELF::getRealStats($fighter, $target)[0];
    $realDef = SELF::getRealStats($target, $target)[1];

    //Create new fight log
    $fightlog = new FGL;
    $fightlog->id_fights = $fightId;
    $fightlog->id_fighter = $target->id;
    $fightlog->round = $round;
    $fightlog->fighter_type = $target->type;

    //Get hp of target from last round
    $lastRound = FGL::where('round', $round-1)
        ->where('id_fighter', $target->id)
        ->where('fighter_type', $target->type)
        ->orderBy('id', 'desc')
        ->first();

    if($_POST['charAction'] == 'def' && $fighter->type == 'm'){
      $damage = $realAttack - ($realDef*1.25)/5;
      $damage < 1 ? $damage = 1 : '';//Damage can't  be negative
      $fightlog->damage = $damage;
      $leftLife = $lastRound->hp - $damage;
    }
    elseif($_POST['charAction'] == 'attack' && $fighter->type == 'm'){
      $damage = $realAttack - ($realDef)/5;
      $damage < 1 ? $damage = 1 : '';//Damage can't  be negative
      $fightlog->damage = $damage;
      $leftLife = $lastRound->hp - $damage;
    }
    else{
      $damage = $realAttack - $realDef/5;
      $damage < 1 ? $damage = 1 : '';//Damage can't  be negative
      $fightlog->damage = $damage;
      $leftLife = $lastRound->hp - $damage;
    }

    if ($leftLife < 0) {
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
    $fightlog->damage = 0;
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
    $fightlog->damage = 0;
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

  public static function getRealStats($attacker, $attacked){
    $realAttack = $attacker->attack;
    $realDef = $attacked->def;
    //Increases the attacker's attack proportionally to the agility difference between attacker and attacked
    if($attacker->agility >= $attacked->agility*2){
      for ($i = 2; $attacker->agility >= ($attacked->agility*$i); $i++){
        $realAttack *= $i;
      }
    }
    //Increase realAttack depend on weight
    for ($i=0; $attacker->weight > $i; $i++){
      $realAttack += $attacker->attack * 0.002;
    }
    //Increase realAttack depend on size
    for ($i=0; $attacker->size > $i; $i++){
      $realAttack += $attacker->attack * 0.001;
    }
    //Increase realDef of attacked depend of weight
    for ($i=0; $attacked->weight > $i; $i++){
      $realDef += $attacked->def * 0.001;
    }
    //Increase realDef of attacked depend of size
    for ($i=0; $attacked->size > $i; $i++){
      $realDef += $attacked->def * 0.002;
    }
    $realAttack = random_int($realAttack * 0.8, $realAttack * 1.2);
    $realDef = random_int($attacked->def * 0.8, $attacked->def * 1.2);
    return [$realAttack, $realDef];
  }

}
