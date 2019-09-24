<?php

namespace ratzslayer3\models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Character extends \Illuminate\Database\Eloquent\Model
{

  use SoftDeletes;

    protected $table = 'characters';
    protected $primaryKey = 'id';
    public $timestamps = true;
}
?>
