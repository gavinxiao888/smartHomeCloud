<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model {

	//
	protected $table = 'address';
	protected $fillable=array(
		'id',
		'user_id',
		'consignee_name',
		'phone',
		'mobilephone',
		'province',
		'city',
		'area',
		'address',
		'code',
		'type',
		'delete'
	);
    public $timestamps=false;
	
	public function user()//多对一
	{
		return $this->belongsTo('App\User', 'user_id');
	}
	public function tAreaByProvice()//多对一
	{
		return $this->belongsTo('App\TArea', 'province');
	}
	//多对一
	public function tAreaByCity()
	{
		return $this->belongsTo('App\TArea', 'city');
	}
	//多对一
	public function tAreaByArea()
	{
		return $this->belongsTo('App\TArea', 'area');
	}

	//获取全部数据
	public function getAllInfo()
	{
		return parent::all();
	}

	//获取单条数据
	public function getInfoByID($id)
	{
		return parent::where('id', $id)->first();
	}

	//获取单条关联数据
	public function getUserInfoByAddressID($id)
	{
		$info = parent::where('id', $id)->first();
		if (!empty($info)) {
			return $info->user();
		}

		return NULL;
	}

	//获取单条关联数据
	public function getTareaInfoByAddressID($id)
	{
		$info = parent::where('id', $id)->first();
		
		if (!empty($info)) {
			return $info->tAreaByArea();
		}

		return NULL;
	}

	//update
	public function updateAction($id, $key, $value)
	{
		return parant::where('id', $id)->update($key, $value);
	}

	//insert 测试用
	public function insertAction($id, $keys, $value)
	{
		return parent::insert([ 'id' => $id,
								'keys' => $keys,
								'value' => $value
								]);
	}
}
