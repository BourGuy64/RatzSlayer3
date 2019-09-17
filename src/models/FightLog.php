<?php

namespace ratzslayer3\models;

class FightLog extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'fight_log';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function fightlog() {
      return $this->belongsTo('ratzslayer3\models\Fight' , 'id_fight');
    }
}

?>
