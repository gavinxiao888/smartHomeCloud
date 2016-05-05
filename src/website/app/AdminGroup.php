<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminGroup extends Model {

	//
	protected $table = 'admin_group';
	protected $fillable=array(
		'id',
		'group_name'
	);
    public $timestamps=false;
	public function admins()//多对多
	{
		return $this->belongsToMany('App\Admin', 'group_admin', 'group_id', 'admin_id');
	}
	public function roles()//多对多
	{
		return $this->belongsToMany('App\Role', 'group_role', 'role_id', 'group_id');
	}
	// public function groupAdmin()
	// {
		// return $this->hasMany('App\GroupAdmin', 'group_id');
	// }
	// public function groupRole()
	// {
		// return $this->hasMany('App\GroupRole', 'group_id');
	// }
}
