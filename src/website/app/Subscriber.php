<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model {

	//
    protected $table = 'subscriber';
    public $fillable=array(
        'firmware_version',
        'id',
        'username',
        'domain',
        'password',
        'email_address',
        'ha1',
        'ha1b',
        'rpid',
        'nickname',
        'common_address',
    );
    public $timestamps = false;
}
