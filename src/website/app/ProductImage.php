<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model {

	//
	protected $table = 'product_image';
	protected $fillable=array(
		'id',
		'product_id',
		'img_path',
		'explain',
		'delete'
	);
    public $timestamps=false;
}
