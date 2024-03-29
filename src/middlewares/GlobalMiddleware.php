<?php namespace ratzslayer3\middlewares;


class GlobalMiddleware {

    public function __invoke($req, $res, $next) {
        if (!isset($_SESSION) || !isset($_SESSION['admin'])) {
            $_SESSION['admin'] = false;
        }
        foreach ($_GET as $key => $value) {
            $_GET[$key] = htmlspecialchars($value);
        }
        foreach ($_POST as $key => $value) {
            $_POST[$key] = htmlspecialchars($value);
        }
        $res = $next($req, $res);
        return $res;
    }

}
