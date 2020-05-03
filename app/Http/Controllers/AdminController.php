<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\SubCategoryRequest;
use App\Services\ShopService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    protected $shopService;

    /**
     * AdminController constructor.
     * @param ShopService $shopService
     */
    public function __construct(ShopService $shopService){
        $this->shopService = $shopService;
    }

    /**
     * @return Application|Factory|View
     */
    public function index(){

        return view('Admin.index');
    }

    /**
     * @return Application|Factory|View
     */
    public function addCategory(){

        return view('Admin.addCategory');
    }

    /**
     * @param CategoryRequest $request
     * @return array
     */
    public function storeCategory(CategoryRequest $request){
        try {
            $this->shopService->createNewCategory($request);
            return ['message'=>'Category saved successfully!'];
        }catch(\Exception $e){
            return ['success'=> false,'message'=>[$e->getMessage()]];
        }
    }

    /**
     * @return Application|Factory|View
     */
    public function addSubCategory(){
        $data['categories'] = $this->shopService->getAllCategories();
        return view('Admin.addSubcategory',$data);
    }

    /**
     * @param SubCategoryRequest $request
     * @return array|RedirectResponse
     */
    public function storeSubCategory(SubCategoryRequest $request){
        try{
            $this->shopService->createNewSubCategory($request);
            return back()->with('success','SubCategory saved successfully!');
        }catch (\Exception $e){
            return ['success'=> false,'message'=>[$e->getMessage()]];
        }
    }
}
