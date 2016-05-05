<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceAttributes extends Model
{
    //
	protected $table = 'device_attributes';
	protected $fillable=array(
	  'id',
	  'name',
	  'manufacturer_id',
	  'product_id',
	  'product_type',
	  'device_type',
	  'charge_id',
	  'init_time',
	  'delete_id',
	  'update_time',
	  'status',
	  'remark'
	);
    public $timestamps=false;
}
