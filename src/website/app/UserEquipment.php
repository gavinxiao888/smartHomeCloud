<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEquipment extends Model {

	//
	protected $table = 'user_equipment';
	protected $fillable=array(
		'id',
		'user_id',
		'device_id',
		'init_time',
		'delete'
	);
    public $timestamps=false;
	public function user()
	{
		return $this->belongsTo('App\User', 'user_id');
	}
	public function device()
	{
		return $this->belongsTo('App\Device', 'device_id');
	}
}
