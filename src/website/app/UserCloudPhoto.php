<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCloudPhoto extends Model {

	//
    protected $table = 'user_cloud_photo';
    public $fillable=array(
        'id',
        'user_id',
        'cp_id',
        'power',
        'type',
        'init_time',
        'update_time',
        'status',
        'remark'
    );
    public $timestamps = false;
}
