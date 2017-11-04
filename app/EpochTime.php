<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EpochTime extends Model
{
    protected $table = 'epochtime';
    protected $primaryKey = 'ping';
    public $incrementing = false;

    public $fillable = [
    	'device_id','ping'
    ];

    public function device()
    {
    	return $this->belongsTo('App\Device','device_id','id');
    }
}
