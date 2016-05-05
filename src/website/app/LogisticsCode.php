<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class LogisticsCode extends Model {

	//
	protected $table = 'logistics_code';
	protected $fillable=array(
		'id',
		'return_apply_id',
		'init_time',
		'company',
		'logistics_code',
		'type',
		'delete'
	);
    public $timestamps=false;
	public function returnApply()//多对一
	{
		return $this->belongsTo('App\ReturnApply', 'return_apply_id');
	}
}
