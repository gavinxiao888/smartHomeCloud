<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GatewayAddress extends Model
{
    protected $table = 'gateway_address';
	protected $fillable=array(
	  'id',
	  'gateway_id',
	  'common_address',
	  'remark'
	);
    public $timestamps=false;
}
