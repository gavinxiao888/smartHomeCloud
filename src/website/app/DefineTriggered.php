<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefineTriggered extends Model
{
    protected $table = 'define_triggered';
    public $fillable=array(
        'id',
        'bind_id',
        'triggered_id',
        'ctrl_id',
        'value',
        'deviceid',
        'rule',
        'init_time',
        'update_time',
        'enable',
        'status'
    );
    public $timestamps = false;
}
