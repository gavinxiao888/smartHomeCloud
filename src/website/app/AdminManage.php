<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminManage extends Model
{
    //
    protected $table = 'admin_manage';
	protected $fillable=array(
	  'id',
	  'username',
	  'nickname',
	  'password',
	  'phone',
	  'email',
	  'sex',
	  'init_time',
	  'update_time',
	  'role',
	  'status',
	  'remark'
	);
    public $timestamps=false;
}
