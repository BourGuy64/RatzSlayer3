<?php

namespace ratzslayer3\models;

use Illuminate/Database/Eloquent/SoftDeletes;

class FightLog extends \Illuminate\Database\Eloquent\Model
{
  use SoftDeletes;

    protected $table = 'fights_log';
    protected $primaryKey = 'id';
    public $timestamps = true;

    public function fightlog() {
      return $this->belongsTo('ratzslayer3\models\Fight' , 'id_fight'); //L'id fight est pas forcément nécessaire
    }
}

?>
