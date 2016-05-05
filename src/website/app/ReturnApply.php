<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ReturnApply extends Model {

	//
	protected $table = 'return_apply';
	protected $fillable=array(
		'id',
		'user_id',
		'init_time',
		'product_id',
		'reason',
		'admin_id',
		'result',
		'check',
		'check_result',
		'status',
		'type',
		'delete'
	);
    public $timestamps=false;
	public function user()//多对一
	{
		return $this->belongsTo('App\User', 'user_id');
	}
	public function admin()//多对一
	{
		return $this->belongsTo('App\Admin', 'admin_id');
	}
	public function device()//多对一
	{
		return $this->belongsTo('App\Device', 'product_id');
	}
	public function logisticsCode()//一对多
	{
		return $this->hasMany('App\LogisticsCode', 'return_apply_id');
	}
}
