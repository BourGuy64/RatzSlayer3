<?php namespace ratzslayer3\middlewares;


class ConnectedRejectMiddleWare {

    public function __invoke($req, $res, $next) {

        if( $_SESSION['admin'] && $req->getUri()->getPath() == "users/login" ) {
            return $res->withRedirect($_SESSION['dir']);
        }

        $res = $next($req, $res);
        return $res;
    }

}
