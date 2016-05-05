<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupRole extends Model {

	//
	protected $table = 'group_role';
	protected $fillable=array(
		'role_id',
		'group_id'
	);
    public $timestamps=false;
	// public function role()
	// {
		// return $this->belongsTo('App\Role', 'role_id');
	// }
	// public function adminGroup()
	// {
		// return $this->belongsTo('App\AdminGroup', 'group_id');
	// }
}
