<?php namespace ratzslayer3\controllers;

use \Psr\Http\Message\ServerRequestInterface    as Request;
use \Psr\Http\Message\ResponseInterface         as Response;
use ratzslayer3\models\User                     as USR;
use ratzslayer3\tools\ImageTools;


class UsersController extends SuperController {

    public function loginForm(Request $req, Response $res, array $args) {
        return $this->views->render($res, 'login.html.twig', ['title' => 'Login','dir' => $this->dir]);
    }

    public function createForm(Request $req, Response $res, array $args) {
        return $this->views->render($res, 'login.html.twig', ['title' => 'Login','dir' => $this->dir]);
    }

    public function login(Request $req, Response $res, array $args) {
        $user = USR::where('username', $_POST['username'])->first();;
        $hashPassword = hash('sha512', $_POST['password'] . SELF::SEL);
        if ( $hashPassword == $user->password) {
            $_SESSION['admin'] = true;
            return $res->withJson('true');
        }
        return $res->withJson('false');
    }

    public function create(Request $req, Response $res, array $args) {

        return $res->withJson("yo");
    }

    private const SEL = 'jaimelesel';

}
