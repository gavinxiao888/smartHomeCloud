<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RobotCtrl extends Model
{
    protected $table = 'robot_ctrl';
    protected $fillable=array(
        'id',
        'bind_id',
        'robot_id',
        'device_id',
        'ctrl_id',
        'value',
        'position',
        'init_time',
        'update_time',
        'enable',
        'status'
    );
    public $timestamps=false;
}
