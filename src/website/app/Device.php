<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model {

	//
	protected $table = 'device';
	protected $fillable=array(
		'id',
		'category_id',
		'name',
		'summary',
		'content',
		'price',
		'init_time',
		'update_time',
		'status',
		'operator_id'
	);
    public $timestamps=false;
	public function userEquipment()//一对多
	{
		return $this->hasMany('App\UserEquipment', 'device_id');
	}
	public function returnApply()//一对多
	{
		return $this->hasMany('App\ReturnApply', 'product_id');
	}
}
