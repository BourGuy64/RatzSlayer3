<?php

namespace ratzslayer3\models;

use Illuminate/Database/Eloquent/SoftDeletes;

class Monster extends \Illuminate\Database\Eloquent\Model
{
  use SoftDeletes;

    protected $table = 'monsters';
    protected $primaryKey = 'id';
    public $timestamps = true;
}
?>
