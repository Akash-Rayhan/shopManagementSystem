<?php


namespace App\Services;


use App\Model\Category;
use App\Model\Product;
use App\Model\ProductVariation;
use App\Model\Shop;
use App\Model\SubCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ShopService
{

    /**
     * @return mixed
     */
    public function authUserShop(){
        $user_id = Auth::id();
        return Shop::where('user_id', $user_id)->first();
    }

    /**
     * @param $request
     */
    public function createNewCategory($request){
        $validated = $request->validated();
        Category::create($validated);
    }

    /**
     * @return Category[]|Collection
     */
    public function getAllCategories(){
        return Category::all();
    }

    /**
     * @param $request
     */
    public function createNewSubCategory($request){
        $validated = $request->validated();
        SubCategory::create($validated);
    }

    /**
     * @param $request
     * @return array
     */
    public function getSubCategoriesOfTheCategory($request){
        $subCategories = SubCategory::where('category_id',$request->id)->get();
        return ['subcategories'=>$subCategories,'status'=>true];
    }



}
