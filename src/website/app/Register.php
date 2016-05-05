<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Register extends Model {

	//
    protected $table = 'register';
    public $fillable=array(
        'id',
        'user',
        'password',
        'type',
        'app_type',
        'app_name',
        'app_key',
        'platform_type',
        'device_id',
        'push_id',
        'captcha',
        'expire_time',
        'init_time',
        'update_time',
        'status',
        'remark'
    );
    public $timestamps = false;
}
