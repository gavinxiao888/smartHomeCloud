<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGatewayBind extends Model
{
    protected $table = 'user_gateway_bind';
    protected $fillable = array(
        'id',
        'user_id',
        'gateway_id',
        'nickname',
        'version',
        'ip',
        'sip_user',
        'init_time',
        'update_time',
        'status',
        'remark',
    );
    public $timestamps = false;
}
