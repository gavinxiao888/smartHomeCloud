<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model {

	//
    protected $table = 'user_device';
    public $fillable=array(
        'id',
        'app_type',
        'app_name',
        'app_key',
        'platform_type',
        'device_id',
        'push_id',
        'init_time',
        'update_time',
        'status',
        'remark'
    );
    public $timestamps = false;
}
