<?php

namespace App\Http\Controllers;

use App\Model\Product;
use App\Model\ProductVariation;
use App\Services\ShopService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    protected $shopService;
    public function __construct(ShopService $shopService)
    {
        $this->shopService = $shopService;
    }
    public function getCategories(){
        $categories = $this->shopService->getAllCategories();
        return response()->json([
            'status'=>true,
            'categories'=> $categories
        ]);
    }
    public function findSubcategory(Request $request){
        $response=$this->shopService->getSubCategoriesOfTheCategory($request);

        return response()->json(['subcategories'=> $response['subcategories'],'status'=>true]);
    }

}
