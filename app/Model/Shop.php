<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable=['name','user_id','size'];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function employee(){
        return $this->hasMany(Employee::class);
    }
    public function product(){
        return $this->hasMany(Product::class);
    }
}
