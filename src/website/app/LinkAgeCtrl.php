<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkAgeCtrl extends Model
{
    protected $table = 'link_age_ctrl';
    public $fillable=array(
        'id',
        'bind_id',
        'link_age_id',
        'ctrl_id',
        'value',
        'deviceid',
        'position',
        'init_time',
        'update_time',
        'enable',
        'status'
    );
    public $timestamps = false;
}
