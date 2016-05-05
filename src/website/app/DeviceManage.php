<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceManage extends Model
{
    //
	protected $table = 'device_manage';
	protected $fillable=array(
	  'id',
	  'bind_id',
	  'device_id',
	  'device_type',
	  'nickname',
	  'location',
	  'init_time',
	  'update_time',
      'version',
	  'status',
	  'remark'
	);
    public $timestamps=false;
}
