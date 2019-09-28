<?php namespace ratzslayer3\controllers;

use \Psr\Http\Message\ServerRequestInterface    as Request;
use \Psr\Http\Message\ResponseInterface         as Response;
use ratzslayer3\models\Monster                  as MST;


class MonstersController extends SuperController {

    public function get(Request $req, Response $res, array $args) {
        $monsters = MST::all();
        return $this->views->render($res, 'fighters.html.twig', ['title' => 'Monsters','dir' => $this->dir, 'fighters' => $monsters]);
    }

    public function createForm(Request $req, Response $res, array $args) {
        return $this->views->render($res, 'form-monster.html.twig', ['title' => 'New character', 'dir' => $this->dir]);
    }

    public function create(Request $req, Response $res, array $args) {

        // test if character is unique
        $monster = MST::where('name', 'like', $_POST['name'])->first();
        if ($monster) {
            return $res->withStatus(400);
        }

        // image upload
        $fileInfo = pathinfo($_FILES['img']['name']);
        $extension = $fileInfo['extension']; // get the extension of the file
        $fileName = $_POST['name'] . '.' . $extension;
        $target = 'src/img/fighters/' . $fileName; // better link maybe ?
        move_uploaded_file($_FILES['img']['tmp_name'], $target);

        $monster = new MST;
        $monster->name      = $_POST['name'];
        $monster->weight    = $_POST['weight'];
        $monster->size      = $_POST['size'];
        $monster->hp        = $_POST['hp'];
        $monster->attack    = $_POST['attack'];
        $monster->def       = $_POST['def'];
        $monster->agility   = $_POST['agility'];
        $monster->picture   = $fileName;
        $monster->save();

        return $res->withJson($monster);
    }

    // public function imageUpload($item)
	// {
	// 	if ($this->imageVerify($_FILES)) {
	// 		if ($item->img) unlink($item->img);
	// 		if (!is_dir('img/')) mkdir('img/');
	// 		$fileName = "img/$item->name-icone.png";
	// 		$resultat = move_uploaded_file($_FILES['icone']['tmp_name'], $fileName);
	// 		// if ($resultat) echo "Transfert réussi";
	// 		// $item->img = $fileName;
	// 		// $item->save();
	// 	}
	// }
    //
	// public static function imageDelete($item)
	// {
	// 	if ($item->img) unlink($item->img);
	// 	$item->img = NULL;
	// 	$item->save();
	// }
    //
	// public static function imageVerify($file)
	// {
	// 	if ($_FILES['img']['error'] > 0) {
	// 		return false;
	// 	}
    //
	// 	if ($_FILES['img']['size'] > 10000000) {
	// 		return false;
	// 	}
    //
	// 	$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
    //
	// 	$extension_upload = strtolower(  substr(  strrchr($_FILES['img']['name'], '.')  ,1)  );
	// 	if ( !in_array($extension_upload, $extensions_valides) ) {
	// 		return false;
	// 	}
	// 	return true;
	// }
}
