<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['name','salary','shop_id','still_working','started_at','ended_at'];
    public function shop(){
        return $this->belongsTo(Shop::class);
    }
}
