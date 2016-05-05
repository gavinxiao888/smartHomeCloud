<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFeedback extends Model
{
    //用户反馈
    protected $table = 'user_feedback';
    public $fillable=array(
        'id',
        'user_id',
        'tel',
        'init_time',
        'remark'
    );
    public $timestamps = false;
}
