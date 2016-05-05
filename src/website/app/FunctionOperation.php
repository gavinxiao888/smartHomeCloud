<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class FunctionOperation extends Model {

	//
	protected $table = 'function_operation';
	protected $fillable=array(
		'id',
		'operation_name',
		'regular',
		'type'
	);
    public $timestamps=false;
	public function rules()//多对多
	{
		return $this->belongsToMany('App\Rule', 'rule_operation', 'operation_id', 'rule_id');
	}
}
