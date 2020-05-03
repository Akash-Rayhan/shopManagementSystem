<?php

namespace App\Http\Controllers;

use App\Services\ShopService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $shopService;
    public function __construct(ShopService $shopService)
    {
        $this->middleware('auth');
        $this->shopService = $shopService;

    }
    public function index(){
        $data['userShop'] = $this->shopService->authUserShop();
        return view('User.index',$data);
    }


}
