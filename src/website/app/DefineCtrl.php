<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefineCtrl extends Model
{
    protected $table = 'define_ctrl';
    public $fillable=array(
        'id',
        'bind_id',
        'ctrl_id',
        'node_id',
        'device_id',
        'action_id',
        'value',
        'init_time',
        'charge_id',
        'enable',
        'update_time',
        'remark',
        'status'
    );
    public $timestamps = false;
}
