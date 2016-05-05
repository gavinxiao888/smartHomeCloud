<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class AppVersionUpdating extends Model {

	//
    protected $table = 'app_version_updating';
    public $fillable=array(
        'id',
        'app_id',
        'version',
        'level',
        'content',
        'init_time',
        'update_time',
        'status',
        'remark'
    );
    public $timestamps = false;
}
