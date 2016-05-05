<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GatewayStatus extends Model
{
    //
    protected $table = 'gateway_status';
	protected $fillable=array(
	  'id',
	  'online',
	  'init_time',
	  'update_time',
	  'status',
	  'remark'
	);
    public $timestamps=false;
}
