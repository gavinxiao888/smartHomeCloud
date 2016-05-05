<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceDetail extends Model
{
    //
	protected $table = 'device_detail';
	protected $fillable=array(
	  'id',
	  'bind_id',
	  'node_id',
	  'cmd_code',
	  'content_type',
	  'content',
	  'endpoint',
	  'init_time',
	  'update_time',
	  'status',
	  'remark'
	);
    public $timestamps=false;
}
