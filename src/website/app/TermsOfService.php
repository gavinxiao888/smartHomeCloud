<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TermsOfService extends Model
{
    protected $table = 'terms_of_service';
    public $fillable=array(
        'id',
        'page_name',
        'content',
        'init_time',
        'charge_id',
        'update_time',
        'status',
        'remark'
    );
    public $timestamps = false;
}
