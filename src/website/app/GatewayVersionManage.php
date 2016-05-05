<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GatewayVersionManage extends Model
{

    protected $table = 'gateway_version_manage';
    public $fillable=array(
        'id',
        'name',
        'href',
        'charage_id',
        'type',
        'init_time',
        'update_time',
        'enable',
        'status',
        'remark'
    );
    public $timestamps = false;
}
