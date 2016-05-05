<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceLog extends Model
{
    //
    protected $table = 'device_log';
	protected $fillable=array(
	  'id',
	  'log_id',
	  'user_id',
	  'bind_id',
	  'device_type',
	  'device_id',
	  'event_time',
	  'ctrl_id',
	  'value',
	  'init_time',
	  'status'
	);
	public $timestamps=false;
}
