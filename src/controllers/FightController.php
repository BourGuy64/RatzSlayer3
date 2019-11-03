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

            $fight      = FGT::where('id', $fightId)->first();
            
            $characters = [
                CHR::where('id', $fight->id_characters)->first(),
                CHR::where('id', $fight->id_characters1)->first(),
                CHR::where('id', $fight->id_characters2)->first(),
            ];
            $monsters = [
                MST::where('id', $fight->id_monsters)->first(),
                MST::where('id', $fight->id_monsters1)->first(),
                MST::where('id', $fight->id_monsters2)->first(),
            ];

            $charLogs = [
                FGL::where('id_fights', $fightId)
                    ->where('fighter_type', 'c')
                    ->where('id_fighter', $characters[0]->id)
                    ->orderBy('id', 'desc')
                    ->get(),
                FGL::where('id_fights', $fightId)
                    ->where('fighter_type', 'c')
                    ->where('id_fighter', $characters[1]->id)
                    ->orderBy('id', 'desc')
                    ->get(),
                FGL::where('id_fights', $fightId)
                    ->where('fighter_type', 'c')
                    ->where('id_fighter', $characters[2]->id)
                    ->orderBy('id', 'desc')
                    ->get(),
            ];
            $mstrLogs = [
                FGL::where('id_fights', $fightId)
                    ->where('fighter_type', 'm')
                    ->where('id_fighter', $monsters[0]->id)
                    ->orderBy('id', 'desc')
                    ->get(),
                FGL::where('id_fights', $fightId)
                    ->where('fighter_type', 'm')
                    ->where('id_fighter', $monsters[1]->id)
                    ->orderBy('id', 'desc')
                    ->get(),
                FGL::where('id_fights', $fightId)
                    ->where('fighter_type', 'm')
                    ->where('id_fighter', $monsters[2]->id)
                    ->orderBy('id', 'desc')
                    ->get(),
            ];

            $winner = $this->winner($logChar[0], $logMonster[0]);

            $twigData = [
                'dir' =>  $this->dir,
                'admin' => $_SESSION['admin'],
                'title' => 'Fight !',
                'character' => $characters,
                'monster' => $monsters,
                'logChar' => $charLogs,
                'logMonster' => $monsterLogs,
                'winner' => $winner,
            ];
            return $this->views->render($res, 'fighting.html.twig', $twigData);
        } else {
            $characters = CHR::all();
            $monsters = MST::all();
            return $this->views->render($res, 'fight.html.twig', ['title' => 'Choose your fighter', 'dir' =>  $this->dir, 'characters' => $characters, 'monsters' => $monsters, 'admin' => $_SESSION['admin']]);
        }
    }

    public function fight(Request $req, Response $res, array $args) {
        $fight = new FGT;
        $fight->winner = 0;

        if ( isset($_POST['char']) && isset($_POST['monster']) ) {
            $character = CHR::find($_POST['char']);
            $monster = MST::find($_POST['monster']);
            $fight->id_characters     = $character->id;
            $fight->id_monsters       = $monster->id;
        } else if ( isset($_POST['chars']) && isset($_POST['monsters']) ) {
            $idCharacters = json_decode($_POST['chars']);
            $idMonsters = json_decode($_POST['monsters']);
            $fight->id_characters   = $idCharacters[0];
            $fight->id_characters2  = $idCharacters[1];
            $fight->id_characters3  = $idCharacters[2];
            $fight->id_monsters     = $idMonsters[0];
            $fight->id_monsters2    = $idMonsters[1];
            $fight->id_monsters3    = $idMonsters[2];
        }

        if(!($character && $monster) && !($idCharacters && $idMonsters)){
            return $res->withStatus(400);
        }

        $fight->save();

        if ( isset($character) && isset($monster) ) {
            FGLC::roundZero($character, $fight->id);
            FGLC::roundZero($monster, $fight->id);
        } else if ( isset($idCharacters) && isset($idMonsters) ) {

            foreach ($idCharacters as $idCharacter) {
                $character = CHR::find($idCharacter);
                FGLC::roundZero($character, $fight->id);
            }
            foreach ($idMonsters as $idMonster) {
                $monster = MST::find($idMonster);
                FGLC::roundZero($monster, $fight->id);
            }
        }

        setcookie("CurrentFight",     $fight->id,     time() + (10 * 365 * 24 * 60 * 60) );
        setcookie("fight",            $fight->id,     time() + (10 * 365 * 24 * 60 * 60) );

        return $res->withRedirect($this->dir . '/fight');
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
}
