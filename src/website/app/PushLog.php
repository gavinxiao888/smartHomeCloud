<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PushLog extends Model
{
    //
    protected $table = 'push_log';
    public $fillable=array(
        'id',
        'type',
        'charge_id',
        'title',
        'content',
        'init_time',
        'update_time',
        'status',
        'remark'
    );
    public $timestamps = false;
}
