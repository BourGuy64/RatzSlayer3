<?php namespace ratzslayer3\middlewares;


class GlobalMiddleware {

    public function __invoke($req, $res, $next) {
        $res = $next($req, $res);
        return $res;
    }

}
