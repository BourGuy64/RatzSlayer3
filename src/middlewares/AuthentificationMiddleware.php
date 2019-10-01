<?php namespace ratzslayer3\middlewares;


class AuthentificationMiddleware {

    public function __invoke($req, $res, $next) {
        if ( !isset($_SESSION['admin']) || !$_SESSION['admin'] ) {
            return $res->withJson($_SESSION['admin']);
            // return $res->withRedirect($_SESSION['dir']);
        }
        $res = $next($req, $res);
        return $res;
    }

}
