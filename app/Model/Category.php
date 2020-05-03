<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable=['name','description'];
    public function subCategory(){
        return $this->hasMany(SubCategory::class);
    }
    public function product(){
        return $this->hasMany(Product::class);
    }
}
