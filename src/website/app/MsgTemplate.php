<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class MsgTemplate extends Model {

	//
	protected $table = 'msg_template';
	protected $fillable=array(
		'id',
		'title',
		'content',
		'delete'
	);
    public $timestamps=false;
}
