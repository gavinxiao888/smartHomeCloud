<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model {

	//
	protected $table = 'banner';
	protected $fillable=array(
		'id',
		'init_time',
		'update_time',
		'post_time',
		'img_path',
		'web_link',
		'mobile_link',
		'delete'
	);
    public $timestamps=false;
}
