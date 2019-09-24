<?php namespace ratzslayer3\controllers;


class SuperController {

    private $c = null;
    protected $views = null;

    public function __construct($container) {
        $this->c = $container;
        $this->dir = $container['dir'];
        $this->views = $container["view"];
    }
}
