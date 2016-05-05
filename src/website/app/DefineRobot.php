<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefineRobot extends Model
{
    protected $table = 'define_robot';
    protected $fillable=array(
        'id',
        'bind_id',
        'robot_id',
        'robot_name',
        'length',
        'begin',
        'end',
        'create_time',
        'init_time',
        'charge_id',
        'update_time',
        'enable',
        'status',
        'is_init'
    );
    public $timestamps=false;

}
