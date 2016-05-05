<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefineLinkAge extends Model
{
    protected $table = 'define_link_age';
    public $fillable=array(
        'id',
        'bind_id',
        'link_age_id',
        'link_age_name',
        'length',
        'begin',
        'end',
        'triggered',
        'create_time',
        'init_time',
        'user_id',
        'update_time',
        'enable',
        'remark',
        'status'
    );
    public $timestamps = false;
}
