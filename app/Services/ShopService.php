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
        try {
            $user_id = Auth::id();
            return Shop::where('user_id', $user_id)->first();
        }catch (\Exception $e){
            return ['status'=> false, 'message'=> $e->getMessage()];
        }

    }

    /**
     * @param $request
     * @return array
     */
    public function createNewCategory($request){
        try {
            $validated = $request->validated();
            Category::create($validated);
        }catch (\Exception $e){
            return ['status'=> false, 'message'=> $e->getMessage()];
        }

    }

    /**
     * @return Category[]|Collection
     */
    public function getAllCategories(){
        return Category::all();
    }

    /**
     * @param $request
     * @return array
     */
    public function createNewSubCategory($request){
        try {
            $validated = $request->validated();
            SubCategory::create($validated);
        }catch (\Exception $e){
            return ['status'=> false, 'message'=> $e->getMessage()];
        }

    }

    /**
     * @param $request
     * @return array
     */
    public function getSubCategoriesOfTheCategory($request){
        try {
            $subCategories = SubCategory::where('category_id',$request->id)->get();
            return ['subcategories'=>$subCategories,'status'=>true];
        }catch(\Exception $e){
            return ['status'=> false, 'message'=> $e->getMessage()];
        }

    }



}
