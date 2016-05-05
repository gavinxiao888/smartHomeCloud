<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GatewayRemark extends Model
{
    protected $table = 'gateway_remark';
    public $fillable=array(
        'id',
        'province',
        'city',
        'county',
        'community',
        'floor',
        'init_time',
        'charge_id',
        'update_time',
        'status',
        'remark'
    );
    public $timestamps = false;
}
