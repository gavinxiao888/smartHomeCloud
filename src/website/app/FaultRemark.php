<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FaultRemark extends Model
{
    protected $table = 'fault_remark';
	protected $fillable=array(
	  'id',
	  'name',
	  'province',
	  'city',
	  'county',
	  'community',
	  'floor',
	  'tel',
	  'fault_time',
	  'charge_id',
	  'init_time',
	  'update_time',
	  'status',
	  'remark'
	);
    public $timestamps=false;
}
