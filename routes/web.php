<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();



Route::group(['middleware' => 'checkAdminOrUser'], function () {
    Route::get('/home', 'HomeController@index')->name('home');
});


Route::group(['middleware' => 'checkAdmin'], function () {
    Route::get('/admin', 'AdminController@index')->name('admin');
    Route::get('/addCategory', 'AdminController@addCategory')->name('addCategory');
    Route::post('/addCategory', 'AdminController@storeCategory')->name('storeCategory');
    Route::get('/addSubCategory', 'AdminController@addSubCategory')->name('addSubCategory');
    Route::post('/addSubCategory', 'AdminController@storeSubCategory')->name('storeSubCategory');
});


Route::group(['middleware' => 'checkUser'], function () {
    Route::get('/user', 'UserController@index')->name('user');
    Route::get('/addProduct', 'ProductController@addProduct')->name('addProduct');
    Route::get('/getCategories','ShopController@getCategories')->name('getCategories');
    Route::get('/findSubcategory','ShopController@findSubcategory')->name('findSubcategory');
    Route::post('/addProduct', 'ProductController@storeProduct')->name('storeProduct');
    Route::post('/deleteProduct','ProductController@deleteProduct')->name('deleteProduct');
    Route::post('/updateProduct','ProductController@updateProduct')->name('updateProduct');
    Route::get('/getProducts', 'ProductController@getProducts')->name('getProducts');
    Route::post('/addVariation', 'ProductController@addVariation')->name('addVariation');
    Route::get('/getVariations','ProductController@getVariations')->name('getVariations');
    Route::get('/getEmployees','EmployeeController@getEmployees')->name('getEmployees');
    Route::get('/addEmployee','EmployeeController@addEmployee')->name('addEmployee');
    Route::post('/storeEmployee','EmployeeController@storeEmployee')->name('storeEmployee');
    Route::post('/editEmployee','EmployeeController@editEmployee')->name('editEmployee');
    Route::post('/deleteEmployee','EmployeeController@deleteEmployee')->name('deleteEmployee');
    Route::get('/productSearch','ProductController@productSearch')->name('productSearch');
    Route::get('/filterProduct','ProductController@filterProduct')->name('filterProduct');
    Route::get('/searchEmployee','EmployeeController@searchEmployee')->name('searchEmployee');
    Route::get('/filterEmployee','EmployeeController@filterEmployee')->name('filterEmployee');
    Route::get('/searchEmployeeName','EmployeeController@searchEmployeeName')->name('searchEmployeeName');
    Route::get('/employees/export','EmployeeController@export')->name('employeeExport');
});
