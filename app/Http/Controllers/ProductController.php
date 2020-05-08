<?php

namespace App\Http\Controllers;


use App\Http\Requests\ProductRequest;
use App\Http\Requests\VariationRequest;
use App\Model\Product;
use App\Model\ProductVariation;
use App\Services\ProductService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    protected $productService;

    /**
     * ProductController constructor.
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService){
        $this->productService = $productService;
    }

    /**
     * @return Application|Factory|View
     */
    public function addProduct(){
        return view('User.addProduct');
    }

    /**
     * @return JsonResponse
     */
    public function getProducts(){
        return response()->json([
            'success' => true,
            'products' => $this->productService->getAllProducts()
        ]);
    }

    /**
     * @param ProductRequest $request
     * @return JsonResponse
     */
    public function storeProduct(ProductRequest $request){
       $response=$this->productService->createNewProduct($request);

        return response()->json([
            'status'=> $response['status'],
            'success'=>'Product saved'
        ]);
    }

    /**
     * @param Request $request
     */
    public function updateProduct(Request $request){
        $this->productService->updateProduct($request);
    }

    /**
     * @param VariationRequest $request
     * @return JsonResponse
     */
    public function addVariation(VariationRequest $request){
        $response=$this->productService->createNewVariation($request);

        return response()->json([
            'status'=> $response['status'],
            'success'=>"Variation saved"
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function getVariations(Request $request){
        $response = $this->productService->getAllVariations($request);
        return response()->json([
            'variations'=>$response['variations'],
            'status'=>true
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteProduct(Request $request){
        $response = $this->productService->deleteProduct($request);
        return response()->json([
            'status'=> $response['status']
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function productSearch(Request $request){
        $response = $this->productService->searchProduct($request);
        return response()->json([
            'products'=> $response['products'],
            'status'=> $response['status']
        ]);
    }

    /**
     * @param Request $request
     * @param Product $product
     * @param ProductVariation $productVariation
     * @return JsonResponse
     */
    public function filterProduct(Request $request, Product $product, ProductVariation $productVariation){
        $response = $this->productService->filterProducts($request,$product,$productVariation);
        return response()->json([
            'filteredProducts' => $response['filteredProducts'],
            'status' => $response['status']
        ]);
    }
}
