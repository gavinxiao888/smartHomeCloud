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
}
