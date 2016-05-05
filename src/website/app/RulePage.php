<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class RulePage extends Model {

	//
	protected $table = 'rule_page';
	protected $fillable=array(
		'rule_id',
		'page_elements_id'
	);
    public $timestamps=false;
	// public function rule()
	// {
		// return $this->belongsTo('App\Rule', 'rule_id');
	// }
}
