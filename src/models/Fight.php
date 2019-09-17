<?php

namespace ratzslayer3\models;

class Fight extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'fight';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function fightlog() {
        return $this->hasMany('ratzslayer3\models\FightLog', 'id_fight');
    }
}
?>
