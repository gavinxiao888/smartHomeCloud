<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceType extends Model
{
    //
    protected $table = 'device_type';
	protected $fillable=array(
	  'id',
	  'name',
        'type_condition',
        'type_feature',
        'type_controll',
	  'charge_id',
	  'init_time',
	  'delete_id',
	  'update_time',
	  'status',
	  'remark'
	);
    public $timestamps=false;
}
