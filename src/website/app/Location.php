<?php

/* * **********************************************************
  Copyright (C), 2014-2015, Everyoo Tech. Co., Ltd.
  @FileName: Location.php
  @Author: 于汝意  onceruyi@gmail.com
  @Version :  1.0
  @Date: 2016-3-9  15:20:40
  @Description:
 * ********************************************************* */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //
    protected $table = 'location';
	protected $fillable=array(
	  'id',
            'ruid',
            'username',
            'domain',
            'contact',
            'common_address',
            'received',
            'path',
            'expires',
            'q',
            'callid',
            'cseq',
            'last_modified',
            'flags',
            'cflags',
            'user_agent',
            'socket',
            'methods',
            'instance',
            'reg_id',
            'server_id',
            'connection_id',
            'keepalive',
            'partition'
	);
    public $timestamps=false;
}
