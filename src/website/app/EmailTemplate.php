<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model {

	//
	protected $table = 'email_template';
	protected $fillable=array(
		'id',
		'title',
		'content',
		'delete'
	);
    public $timestamps=false;
}
