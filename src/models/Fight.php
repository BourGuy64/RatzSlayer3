<?php

namespace ratzslayer3\models;

use Illuminate/Database/Eloquent/SoftDeletes;

class Fight extends \Illuminate\Database\Eloquent\Model
{
  use SoftDeletes;

    protected $table = 'fights';
    protected $primaryKey = 'id';
    public $timestamps = true;

    public function fightlog() {
        return $this->hasMany('ratzslayer3\models\FightLog', 'id_fight', 'id');
    }
}
?>
