<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupAdmin extends Model {

	//
	protected $table = 'group_admin';
	protected $fillable=array(
		'group_id',
		'admin_id'
	);
    public $timestamps=false;
	// public function adminGroup()
	// {
		// return $this->belongsTo('App\AdminGroup', 'group_id');
	// }
	// public function adminGroup()
	// {
		// return $this->belongsTo('App\Admin', 'admin_id');
	// }
}
