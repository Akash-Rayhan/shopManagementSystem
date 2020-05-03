<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable=['name','subcategory_id','category_id','shop_id'];
    public function subCategory(){
        return $this->belongsTo(SubCategory::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function shop(){
        return $this->belongsTo(Shop::class);
    }
    public function productVariation(){
        return $this->hasMany(ProductVariation::class);
    }

}
