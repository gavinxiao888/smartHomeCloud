<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceAction extends Model
{
    protected $table = 'device_action';
    public $fillable=array(
        'id',
        'device_type_id',
        'action_id',
        'init_time',
        'charge_id',
        'enable',
        'update_time',
        'remark',
        'status'
    );
    public $timestamps = false;
}
