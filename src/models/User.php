<?php namespace ratzslayer3\models;

use Illuminate\Database\Eloquent\SoftDeletes;


class User extends \Illuminate\Database\Eloquent\Model {

    use SoftDeletes;

    protected $table = 'users';
    protected $primaryKey = 'id';

    public $timestamps = true;
}
