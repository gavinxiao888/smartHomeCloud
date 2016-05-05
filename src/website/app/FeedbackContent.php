<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedbackContent extends Model
{
    //
    protected $table = 'feedback_content';
    public $fillable=array(
        'id',
        'feedback_id',
        'content'
    );
    public $timestamps = false;
}
