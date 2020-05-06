<?php


namespace App\Services;


use App\Http\Requests\ProductRequest;
use App\Model\Category;
use App\Model\Product;
use App\Model\ProductVariation;
use App\Model\Shop;
use App\Model\SubCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductService
{

    /**
     * @param $request
     * @return array|bool[]
     */
    public function createNewProduct($request){

        try {
            //dd($request->name);
            $userShop = Shop::where('user_id', Auth::id())->first();
            $shopId = $userShop->id;
            Product::create([
                'name'=>$request->name,
                'subcategory_id'=>$request->category_id,
                'category_id'=>$request->subcategory_id,
                'shop_id'=>$shopId
            ]);

            return ['status' => true];
        }catch (\Exception $e){

            return ['status'=> false, 'message'=> $e->getMessage()];
        }

    }

    /**
     * @return array
     */
    public function getAllProducts(){
        try {
            $userShop = Shop::where('user_id', Auth::id())->first();
            $shopId = $userShop->id;

            return Product::where('shop_id', $shopId)->get();
        }catch (\Exception $e){

            return ['status'=> false, 'message'=> $e->getMessage()];
        }

    }

    /**
     * @param $request
     * @return array|bool[]
     */
    public function createNewVariation($request){
        try {
                ProductVariation::create([
                    'name' => $request->name,
                    'quantity' => $request->quantity,
                    'price' => $request->price,
                    'product_id' => $request->product_id
                ]);

                return ['status' => true];
        }catch (\Exception $e){

            return ['status'=> false, 'message'=> $e->getMessage()];
        }

    }

    /**
     * @param $request
     * @return array
     */
    public function getAllVariations($request){
        try {
            $variations=ProductVariation::where('product_id',$request->id)->get();
            
            return ['variations'=>$variations];
        }catch (\Exception $e){

            return ['status'=> false, 'message'=> $e->getMessage()];
        }

    }

    /**
     * @param $request
     * @return array
     */
    public function updateProduct($request){
        try {
            Product::where('id',$request->id)->update([
                'name'=> $request->name,
                'category_id'=>$request->category_id,
                'subcategory_id'=> $request->subcategory_id
            ]);
        }catch (\Exception $e){

            return ['status'=> false, 'message'=> $e->getMessage()];
        }

    }

    /**
     * @param $request
     * @return array
     */
    public function deleteProduct($request){
        try {
            DB::beginTransaction();
            Product::find($request->id)->delete();
            ProductVariation::where('product_id',$request->id)->delete();
            DB::commit();

            return ['status'=> true];
        }catch (\Exception $e){

            return ['status'=> false, 'message'=> $e->getMessage()];
        }

    }

    /**
     * @param $request
     * @return array|bool[]
     */
    public function searchProduct($request){

        try {
            $name = $request->name;
            $userShop = Shop::where('user_id', Auth::id())->first();
            $shopId = $userShop->id;
            $products = Product::select(
                'product_variations.name as name',
                'product_variations.quantity as quantity',
                'product_variations.price as price'
            )
                ->leftJoin('product_variations',['product_variations.product_id' => 'products.id']);

            $products->where('products.shop_id', $shopId)
                ->where(function ($products) use ($name){
                    $products->where('products.name','like',"%{$name}%");
                    $products->orwhere('products_variations.name','like',"%{$name}%");
            });

            return ['success'=> true, 'products'=> $products->get()];
        }catch(\Exception $e){

            return ['success'=> false];
        }

    }

    /**
     * @param $request
     * @param $product
     * @param $productVariation
     * @return array
     */
    public function filterProducts($request, $product, $productVariation){
        try {
            $category = $request->category;
            $subcategory = $request->subcategory;
            $priceRange = $request->priceRange;
            $quantityRange = $request->quantityRange;
            $userShop = Shop::where('user_id', Auth::id())->first();
            $shopId = $userShop->id;
            $filteredProducts = null;

            if(($category) || ($subcategory) || ($priceRange) || ($quantityRange)){
                $product = $product->newQuery();
                if(($category)){
                    $product->where('category_id',$category);
                }
                if(($subcategory)){
                    $product->where('subcategory_id',$subcategory);
                }
                $productIds = $product->where('shop_id',$shopId)->pluck('id');
                $productVariation = $productVariation->newQuery();
                if(($priceRange)){
                    $productVariation->whereBetween('price',$priceRange);
                }
                if(($quantityRange)){
                    $productVariation->whereBetween('quantity',$quantityRange);
                }
                $filteredProducts = $productVariation->whereIn('product_id',$productIds)->get();
            }

            return ['filteredProducts'=> $filteredProducts,'status'=> true];
        }catch (\Exception $e){

            return ['status'=> false];
        }


    }
}
