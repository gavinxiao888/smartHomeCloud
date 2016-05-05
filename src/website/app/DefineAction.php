<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefineAction extends Model
{
    protected $table = 'define_action';
    public $fillable=array(
        'id',
        'action_name',
        'content',
        'protocol',
        'init_time',
        'charge_id',
        'enable',
        'update_time',
        'remark',
        'status'
    );
    public $timestamps = false;
}
