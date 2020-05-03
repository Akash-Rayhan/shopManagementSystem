<?php

namespace App\Http\Controllers;


use App\Model\Product;
use App\Model\ProductVariation;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function addProduct(){
        return view('User.addProduct');
    }

    public function getProducts(){
        return response()->json([
            'success' => true,
            'products' => $this->productService->getAllProducts()
        ]);
    }
    public function storeProduct(Request $request){
       $response=$this->productService->createNewProduct($request);
        if($response['status']){

            return response()->json(['success'=> 'Product saved','status'=> true]);
        }

        return response()->json(['error'=> $response['error'], 'status'=> false]);
    }
    public function updateProduct(Request $request){
        $this->productService->updateProduct($request);
    }
    public function addVariation(Request $request){
        $response=$this->productService->createNewVariation($request);
        if($response['status']){

            return response()->json(['success'=> 'Variation saved','status'=> true]);
        }

        return response()->json(['error'=> $response['error'], 'status'=> false]);
    }
    public function getVariations(Request $request){
        $response = $this->productService->getAllVariations($request);
        return response()->json(['variations'=>$response['variations'],'status'=>true]);
    }
    public function deleteProduct(Request $request){
        return $this->productService->deleteProduct($request);
    }
    public function productSearch(Request $request){
        $response = $this->productService->searchProduct($request);
        return response()->json(['products'=> $response['products'], 'status'=> $response['status']]);
    }
    public function filterProduct(Request $request,Product $product,ProductVariation $productVariation){
        $response = $this->productService->filterProducts($request,$product,$productVariation);
        return response()->json(['filteredProducts' => $response['filteredProducts'],'status' => $response['status']]);
    }
}
