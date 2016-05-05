<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class GatewayUserRemark extends Model {

	//
    protected $table = 'gateway_user_remark';
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
