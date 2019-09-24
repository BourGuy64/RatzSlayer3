<?php

namespace ratzslayer3\controller;

abstract class SuperController{

  private $c = null;
  protected $views = null:

  public function _construct($container){
    $this->c = $container;
    $this->viw = $container["view"];
  }
}

?>
