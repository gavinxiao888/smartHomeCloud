<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UserOldpasswd extends Model {

	//
	protected $table = 'user_oldpasswd';
	protected $fillable=array(
		'id',
		'user_id',
		'init_time',
		'passwd'
	);
    public $timestamps=false;
	public function user()
	{
		return $this->belongsTo('App\User', 'user_id');
	}
}
