<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $fillable = ['name','category_id','description'];
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function product(){
        return $this->hasMany(Product::class);
    }
}
