<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    protected $fillable = ['name','product_id','quantity','price'];
    public function product(){
        return $this->belongsTo(Product::class);
    }

}
