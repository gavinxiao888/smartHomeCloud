<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceRelation extends Model
{
    //
	protected $table = 'device_relation';
	protected $fillable=array(
	  'id',
	  'gateway_id',
	  'device_id',
	  'device_type'
	);
    public $timestamps=false;
}
