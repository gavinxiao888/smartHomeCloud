<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductProperty extends Model {

	//
	protected $table = 'product_property';
	protected $fillable=array(
		'id',
		'product_id',
		'product_key',
		'product_value',
		'number',
		'delete'
	);
    public $timestamps=false;
}
