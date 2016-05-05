<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

	//
    protected $table = 'user';
    public $fillable=array(
        'id',
        'init_time',
        'email',
        'phone',
        'passwd',
        'delete'
    );
    public $timestamps = false;
}
