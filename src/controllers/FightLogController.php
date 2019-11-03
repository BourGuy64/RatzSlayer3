<?php namespace ratzslayer3\controllers;

use \Psr\Http\Message\ServerRequestInterface    as Request;
use \Psr\Http\Message\ResponseInterface         as Response;
use ratzslayer3\models\Character                as CHR;
use ratzslayer3\models\Monster                  as MST;
use ratzslayer3\models\FightLog                 as FGL;
use ratzslayer3\models\Fight                    as FGT;
use ratzslayer3\controllers\FightController     as FGTC;

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

        $twigData = [
            'dir'       =>  $this->dir,
            'fighter'   => $fighter,
            'log'       => $log
        ];
        return $this->views->render($res, 'fightlog.html.twig', $twigData);
    }

    public function nextRound(Request $req, Response $res, array $args) {

        $fight      = FGT::find($_POST['fightId']);

        $charDatas = json_decode(htmlspecialchars_decode($_POST['chars']));
        $monsterDatas = json_decode(htmlspecialchars_decode($_POST['monsters']));

        $characters = [];
        foreach ($charDatas as $charData) {
            $characters[] = CHR::find($charData->id);
        }
        $characters = $this->lifeOrder($characters);

        $monsters = [];
        foreach ($monsterDatas as $monsterData) {
            $monsters[] = MST::find($monsterData->id);
        }
        $monsters = $this->lifeOrder($monsters);

        $lastRound = FGL::orderBy('round', 'desc')
            ->where('id_fights', $fight->id)
            ->first();
        $currentRound = $lastRound->round + 1;

        $this->initCurrentRoundLog($characters, $monsters, $fight->id, $currentRound);

        $fighters = $this->attackOrder($characters, $monsters);

        foreach ($fighters as $fighter) {
            $fighterLog = FGL::where('round', $currentRound)
                ->where('id_fighter', $fighter->id)
                ->where('fighter_type', $fighter->type)
                ->orderBy('id', 'desc')
                ->first();

            if ($fighterLog->hp > 0) {
                switch ($fighter->type) {
                    case 'c':
                        $attacked = null;
                        foreach ($monsters as $monster) {
                            if (!$attacked && $monster->hp > 0) {
                                $attacked = $monster;
                            }
                        }
                        $this->doAttack($fighter, $attacked, $fight->id, $currentRound);
                        break;
                    case 'm':
                        $attacked = null;
                        foreach ($characters as $character) {
                            if (!$attacked && $character->hp > 0) {
                                $attacked = $character;
                            }
                        }
                        $this->doAttack($fighter, $attacked, $fight->id, $currentRound);
                        break;
                }
            }
        }

        return $res->withJson(FGTC::winner($_POST['fightId']));
    }

    public static function doAttack($fighter, $target, $fightId, $round){
        $realAttack = SELF::getRealStats($fighter, $target)[0];
        $realDef = SELF::getRealStats($target, $target)[1];

        $fightLog = FGL::where('round', $round)
            ->where('id_fighter', $target->id)
            ->where('fighter_type', $target->type)
            ->orderBy('id', 'desc')
            ->first();

        $damage = $realAttack - $realDef/5;
        $leftLife = $fightLog->hp - ($realAttack - $realDef/5);

        $fightLog->damage += $damage;
        $leftLife = $fightLog->hp - $damage;

        if ($leftLife < 0) {
            $leftLife = 0;
        }

        $fightLog->hp = $leftLife;
        $fightLog->save();
    }

    public function attackOrder($characters, $monsters) {
        $order = array_merge($characters, $monsters);
        $agility = array_column($order, 'agility');
        array_multisort($agility, SORT_DESC, $order);
        return $order;
    }

    public function lifeOrder($fighters) {
        $hp = array_column($fighters, 'hp');
        array_multisort($hp, SORT_DESC, $fighters);
        return $fighters;
    }

    public function initCurrentRoundLog($characters, $monsters, $fightId, $round) {
        foreach ($characters as $character) {
            $lastRound = FGL::where('round', $round-1)
                ->where('id_fighter', $character->id)
                ->where('fighter_type', $character->type)
                ->orderBy('id', 'desc')
                ->first();

            $fightlog = new FGL;
            $fightlog->id_fights = $fightId;
            $fightlog->id_fighter = $character->id;
            $fightlog->round = $round;
            $fightlog->fighter_type = $character->type;
            $fightlog->damage = 0;
            $fightlog->hp = $lastRound->hp;
            $fightlog->save();
        }

        foreach ($monsters as $monster) {
            $lastRound = FGL::where('round', $round-1)
                ->where('id_fighter', $monster->id)
                ->where('fighter_type', $monster->type)
                ->orderBy('id', 'desc')
                ->first();

            $fightlog = new FGL;
            $fightlog->id_fights = $fightId;
            $fightlog->id_fighter = $monster->id;
            $fightlog->round = $round;
            $fightlog->fighter_type = $monster->type;
            $fightlog->damage = 0;
            $fightlog->hp = $lastRound->hp;
            $fightlog->save();
        }
    }

    // Init fight log
    public static function roundZero($fighter, $fightId){
        $fightlog = new FGL;
        $fightlog->id_fights = $fightId;
        $fightlog->id_fighter = $fighter->id;
        $fightlog->round = 0;
        $fightlog->hp = $fighter->hp;

        if ($fighter->type == 'c') {
            $fightlog->fighter_type = 'c';
        } else if($fighter->type == 'm') {
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
