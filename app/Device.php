<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'device';
    protected $primaryKey = 'id';
    public $incrementing = false;

    public $fillable = [
    	'id'
    ];

    public function epochtime()
    {
    	return $this->hasMany('App\EpochTime','device_id','id');
    }
}
