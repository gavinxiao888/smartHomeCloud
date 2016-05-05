<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class RuleOperation extends Model {

	//
	protected $table = 'rule_operation';
	protected $fillable=array(
		'operation_id',
		'rule_id'
	);
    public $timestamps=false;
	// public function rule()
	// {
		// return $this->belongsTo('App\Rule', 'rule_id');
	// }
}
