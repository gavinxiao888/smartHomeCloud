<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SendEmailRecord extends Model {

    //
    protected $table = 'send_email_record';
    public $fillable = array(
        'id',
        'template_id',
        'title',
        'from',
        'status',
        'init_time'
    );
    public $timestamps = false;

}
