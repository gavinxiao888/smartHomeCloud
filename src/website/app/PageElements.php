<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PageElements extends Model {

	//
	protected $table = 'page_elements';
	protected $fillable=array(
		'id',
		'page_path',
		'page_emements_code'
	);
    public $timestamps=false;
	public function rules()//多对多
	{
		return $this->belongsToMany('App\Rule', 'rule_page', 'rule_id', 'page_elements_id');
	}
}
