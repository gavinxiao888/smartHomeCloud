<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

	//
	protected $table = 'role';
	protected $fillable=array(
		'id',
		'name'
	);
    public $timestamps=false;
	
	public function rules()//多对多
	{
		return $this->belongsToMany('\App\Rule', 'role_rule', 'role_id', 'rule_id');
	}
	public function admins()//多对多
	{
		return $this->belongsToMany('\App\Admin');
	}
}
