<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model {

	//
    protected $table = 'token';
    public $fillable=array(
        'id',
        'type',
        'user_id',
        'token',
        'device_id',
        'init_time',
        'update_time',
        'end_time',
        'status',
        'remark'
    );
    public $timestamps = false;
}
