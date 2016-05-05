<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model {

	//
    protected $table = 'user_info';
    public $fillable=array(
        'id',
        'user_id',
        'nickname',
        'real_name',
        'gender',
        'birthday',
        'problem_first',
        'problem_second',
        'problem_third',
        'answer_first',
        'answer_second',
        'answer_third',
        'headimg',
        'signature',
        'safe_level',
        'delete'
    );
    public $timestamps = false;
}
