<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefineTimer extends Model
{
    protected $table = 'define_timer';
    public $fillable=array(
        'id',
        'bind_id',
        'timer_id',
        'ctrl_id',
        'device_id',
        'alarm_time',
        'loop',
        'value',
        'create_time',
        'init_time',
        'charge_id',
        'update_time',
        'enable',
        'remark',
        'status'
    );
    public $timestamps = false;
}
