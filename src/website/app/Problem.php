<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Problem extends Model {

	//
	protected $table = 'problem';
	protected $fillable=array(
		'id',
		'problem'
	);
    public $timestamps=false;
}
