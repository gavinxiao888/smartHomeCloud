<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class DownloadResource extends Model {

	//
	protected $table = 'download_resource';
	protected $fillable=array(
		'id',
		'init_time',
		'update_time',
		'post_time',
		'title',
		'img_path',
		'download_link',
		'type',
		'delete'
	);
    public $timestamps=false;
}
