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
        // return $this->views->render($res, 'fightlog.html.twig', ['dir' =>  $this->dir, 'character' => $character, 'monster' => $monster, 'logChar' => $logChar, 'logMonster' => $logMonster, ]);

        $value = 0;
        if ($logChar->hp <= 0) {
            $value = 'm';
        } else if ($logMonster->hp <= 0) {
            $value = 'c';
        }

        // return $res->withJson($logChar);
        return $res->getBody()->write($value);


        // $winner = $this->winner($fight->id);
        // $fight->winner = $winner;
        // $fight->save();
        // $logChar = FGL::where('id_fights', $fight->id)->where('fighter_type', 'c')->orderBy('id', 'asc')->get();
        // $logMonster = FGL::where('id_fights', $fight->id)->where('fighter_type', 'm')->orderBy('id', 'asc')->get();
        // setcookie("CurrentFight", $fight->id, time() + (10 * 365 * 24 * 60 * 60));
        // return $this->views->render($res, 'fighting.html.twig', ['title' => 'Fight !', 'dir' =>  $this->dir, 'character' => $character, 'monster' => $monster, 'logChar' => $logChar, 'logMonster' => $logMonster, 'winner' => $winner, 'admin' => $_SESSION['admin']]);
    }

  public static function doAttack($fighter, $target, $fightId, $round){
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

    $fightlog->damage = $fighter->attack;
    $leftLife = $lastRound->hp - $fighter->attack;

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
