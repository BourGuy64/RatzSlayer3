<?php namespace ratzslayer3\middlewares;


class GlobalMiddleware {

    public function __invoke($req, $res, $next) {
        if (!isset($_SESSION) || !isset($_SESSION['admin'])) {
            $_SESSION['admin'] = false;
        }
        $res = $next($req, $res);
        return $res;
    }

}
