<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SendSMSRecord extends Model {

	//
    protected $table = 'send_sms_record';
    public $fillable=array(
        'id',
        'phone',
        'content',
        'send_time',
        'init_time',
        'status',
        'remark'
    );
    public $timestamps = false;
}
