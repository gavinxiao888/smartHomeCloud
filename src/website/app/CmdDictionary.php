<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CmdDictionary extends Model
{
    protected $table = 'cmd_dictionary';
	protected $fillable=array(
	  'id',
	  'name',
	  'cmd_code',
	  'content_type',
	  'type',
	  'content',
	  'unit',
	  'init_time',
	  'delete_id',
	  'update_time',
	  'status',
	  'remark'
	);
    public $timestamps=false;
}
