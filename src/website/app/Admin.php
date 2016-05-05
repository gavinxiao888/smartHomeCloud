<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model {

	//
	protected $table = 'admin';
	protected $fillable=array(
		'id',
		'init_time',
		'nickname',
		'passwd'
	);
    public $timestamps=false;
	public function groupAdmin()//一对多
	{
		return $this->hasMany('App\GroupAdmin', 'admin_id');
	}
	public function returnApply()//一对多
	{
		return $this->hasMany('App\ReturnApply', 'admin_id');
	}
	public function roles()//多对多
	{
		return $this->belongsToMany('App\Role', 'admin_role', 'admin_id', 'role_id');
	}
}
