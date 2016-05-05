<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ThirdParties extends Model {

	//
    protected $table = 'third_parties';
    public $fillable=array(
        'id',
        'user_id',
        'app_id',
        'platform',
        'device_id',
        'third_type',
        'third_id',
        'third_name',
        'init_time',
        'update_time',
        'role',
        'status',
        'remark'

    );
    public $timestamps = false;
}
