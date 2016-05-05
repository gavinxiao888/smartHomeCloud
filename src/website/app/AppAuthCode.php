<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class AppAuthCode extends Model {

	//
    protected $table = 'app_auth_code';
    public $fillable=array(
        'id',
        'code',
        'user_id',
        'type',
        'user_name',
        'device_id',
        'app_id',
        'expire_time',
        'init_time',
        'update_time',
        'status',
        'remark'
    );
    public $timestamps = false;
}
