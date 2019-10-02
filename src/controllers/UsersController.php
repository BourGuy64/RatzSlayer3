<?php namespace ratzslayer3\controllers;

use \Psr\Http\Message\ServerRequestInterface    as Request;
use \Psr\Http\Message\ResponseInterface         as Response;
use ratzslayer3\models\User                     as USR;
use ratzslayer3\tools\ImageTools;


class UsersController extends SuperController {

    public function loginForm(Request $req, Response $res, array $args) {
        return $this->views->render($res, 'login.html.twig', ['title' => 'Login','dir' => $this->dir, 'admin' => $_SESSION['admin']]);
    }

    public function parameters(Request $req, Response $res, array $args) {
        return $this->views->render($res, 'admin-parameters.html.twig', ['title' => 'Parameters','dir' => $this->dir, 'userId' => $_SESSION['admin_id'], 'admin' => $_SESSION['admin']]);
    }

    public function createForm(Request $req, Response $res, array $args) {
        return $this->views->render($res, 'form-user.html.twig', ['title' => 'New user','dir' => $this->dir, 'admin' => $_SESSION['admin']]);
    }

    public function login(Request $req, Response $res, array $args) {
        $user = USR::where('username', $_POST['username'])->first();
        $hashPassword = hash('sha512', $_POST['password'] . SELF::SEL);
        if ( $hashPassword == $user->password) {
            $_SESSION['admin'] = true;
            $_SESSION['admin_id'] = $user->id;
            return $res->withRedirect($_SESSION['dir']);
        }
        return $res->withJson('false');
    }

    public function logout(Request $req, Response $res, array $args) {
        $_SESSION['admin'] = false;
        $_SESSION['admin_id'] = null;
        return $res->withRedirect($_SESSION['dir']);
    }

    public function create(Request $req, Response $res, array $args) {
        // test if username is unique
        $user = USR::where('username', $_POST['username'])->first();
        if ($user) {
            return $res->withStatus(400);
        }


        if ( $_POST['password'] == $_POST['repeatPassword']) {
            $user = new USR;
            $user->username     = $_POST['username'];
            $user->password     = hash('sha512', $_POST['password'] . SELF::SEL);
            $user->save();
            return $res->withJson($user);
        }
        return $res->withStatus(400);
    }

    public function delete(Request $req, Response $res, array $args) {
        $_SESSION['admin'] = false;
        $_SESSION['admin_id'] = null;
        $user = USR::find($args['id']);
        $user->delete();
        return $res->withStatus(200);
    }

    private const SEL = 'jaimelesel';

}
