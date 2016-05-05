<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CloudPhoto extends Model {

	//
	protected $table = 'cloud_photo';
	protected $fillable=array(
		'id',
		'user_id',
		'photo_id',
		'type',
		'img_url',
		'img_name',
		'init_time',
		'update_time',
		'status',
		'remark'
	);
    public $timestamps=false;
	public function user()
	{
		return $this->belongsTo('App\User', 'user_id');
	}
}
