<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model {

	//
	protected $table = 'admin_role';
	protected $fillable=array(
		'admin_id',
		'role_id'
	);
    public $timestamps=false;
	// public function admin()
	// {
		// return $this->belongsTo('App\Admin', 'admin_id');
	// }
	// public function Role()
	// {
		// return $this->belongsTo('App\role', 'role_id');
	// }
}
