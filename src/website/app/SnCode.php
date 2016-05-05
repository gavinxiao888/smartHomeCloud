<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SnCode extends Model {

	//
    protected $table = 'sn_code';
    public $fillable=array(
        'id',
        'sn_code',
        'device_type',
        'sip_id',
        'init_time',
        'status',
        'remark'
    );
    public $timestamps = false;
}
