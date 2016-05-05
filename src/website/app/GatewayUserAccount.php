<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class GatewayUserAccount extends Model {

	//
    protected $table = 'gateway_user_account';
    public $fillable=array(
        'id',
        'bind_id',
        'user_id',
        'role',
        'init_time',
        'update_time',
        'status',
        'remark'
    );
    public $timestamps = false;
}
