@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('addProduct')}}">Add product</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('addEmployee')}}">Add Employee</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('searchEmployee')}}">Search Employee</a>
                </li>
            </ul>
        </div>
                <div class="card">
                    <div class="card-header">Shop Name: {{$userShop->name}}</div>

                    <div class="card-body row  m-0 col-md-12">
                        <div class="card  col-md-4">
                            <div class="card-header m-0">Filters</div>
                            <div class="card-body" id="filter-div">
                                <span>Category <button id="category-button"><i class="fa fa-plus" aria-hidden="true"></i></button></span>
                                <div id="category-div"></div><br>
                                <div id="subcategory-div"></div><br>
                                <span>Price Range <button id="price-range-button"><i class="fa fa-plus" aria-hidden="true"></i></button></span>
                                <div id="price-range-div"></div><br>

                                <span> Quantity <button id="quantity-range-button"><i class="fa fa-plus" aria-hidden="true"></i></button></span>
                                <div id="quantity-range-div"></div><br>
                                <button id="filter-button" >Filter</button>
                            </div>
                        </div>

                        <!-- Product Variations -->
                        <div class="card col-md-8">
                            <div class="card-header">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" placeholder="Product Name" id="product-name"><button id="product-search-button"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                            <div class="card-body" id="variation-dom">
                                <table width="100%" id="variation-table">


                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


@endsection
@section('script')

        <script>
            $(document).ready(function () {
                $('#product-search-button').on('click',function () {
                    let productName = $('#product-name').val();
                    getSearchedProducts(productName);
                    function getSearchedProducts(productName) {
                        $.ajax({
                            url:'{{route('productSearch')}}',
                            method: 'GET',
                            data:{
                                '_token': '{{csrf_token()}}',
                                'name': productName
                            }
                        }).done(function (data) {
                            console.log(data.products);
                            searchedProductsDom(data.products);
                        }).fail(function (error) {
                            console.log(error);
                        })
                    }
                });
                function searchedProductsDom(products){
                    let html='<tr>'+
                        '<th>Name</th>'+
                        '<th>Quantity</th>'+
                        '<th>Price</th>'+
                        '</tr>';
                    for(let i=0; i<products.length; i++){
                        html+='<tr>'+
                            '<td>'+products[i].name+'</td>'+
                            '<td>'+products[i].quantity+'</td>'+
                            '<td>'+products[i].price+'</td>'+
                            '</tr>';
                    }
                    $('#variation-table').html(html);
                }
                $('#price-range-button').on('click',function () {
                    let range = [0,700];
                    priceRangeDom(range);
                })
                function priceRangeDom(range) {
                let html='<div class="container mt-2">\n' +
                    '<div class="row">\n' +
                    '<div class="col-sm-12">\n' +
                    '<div id="price-slider-range"></div>\n' +
                    '</div>\n' +
                    '</div>\n' +
                    '<div class="row slider-labels">\n' +
                    '<div class="col-xs-6 caption ml-2">\n' +
                    '<strong>Min:</strong> <span id="price-slider-range-value1"></span>\n' +
                    '</div>\n' +
                    '<div class="col-xs-6 text-right caption ml-5">\n' +
                    '<strong>Max:</strong>    <span id="price-slider-range-value2"></span>\n' +
                    '</div>\n' +
                    '</div>\n' +
                    '<div class="row">\n' +
                    '<div class="col-sm-12">\n' +
                    '<form>\n' +
                    '<input type="hidden" id="min-price" name="min-value" value="">\n' +
                    '<input type="hidden" id="max-price"  name="max-value" value="">\n' +
                    '</form>\n' +
                    '</div>\n' +
                    '</div>\n' +
                    '</div>';
                $('#price-range-div').html(html);
                    activatePriceSlider(range);
                }
                $('#quantity-range-button').on('click',function () {
                    let range=[0,20];
                    quantityRangeDom(range);
                })
                function quantityRangeDom(range) {
                    let html='<div class="container mt-2">\n' +
                        '<div class="row">\n' +
                        '<div class="col-sm-12">\n' +
                        '<div id="quantity-slider-range"></div>\n' +
                        '</div>\n' +
                        '</div>\n' +
                        '<div class="row slider-labels">\n' +
                        '<div class="col-xs-6 caption ml-2">\n' +
                        '<strong>Min:</strong> <span id="quantity-slider-range-value1"></span>\n' +
                        '</div>\n' +
                        '<div class="col-xs-6 text-right caption ml-5">\n' +
                        '<strong>Max:</strong>    <span id="quantity-slider-range-value2"></span>\n' +
                        '</div>\n' +
                        '</div>\n' +
                        '<div class="row">\n' +
                        '<div class="col-sm-12">\n' +
                        '<form>\n' +
                        '<input type="hidden" id="min-quantity" name="min-value" value="">\n' +
                        '<input type="hidden" id="max-quantity"  name="max-value" value="">\n' +
                        '</form>\n' +
                        '</div>\n' +
                        '</div>\n' +
                        '</div>\n' +
                        '</div>\n' +
                        '</div>';
                    $('#quantity-range-div').html(html);
                    activateQuantitySlider(range);
                }
                $('#category-button').on('click',function () {
                    getCategories();
                });
                function getCategories() {
                    $.ajax({
                        url: '{{route('getCategories')}}',
                        method: 'GET',
                    }).done(function (data) {
                        console.log(data);
                        addCategoryDom(data.categories);
                    }).fail(function (error) {
                        console.log(error);
                    });
                }

                function addCategoryDom(categories) {
                    let option_value='';
                    let html;
                    for(let i=0 ; i<categories.length;i++) {
                        option_value+='<option value="'+categories[i].id+'" class="option_value">'+ categories[i].name +'</option>';
                    }
                    html= '<label>Category Name</label>'+'<select name="category_id" class="form-control select2-multiple" id="category_id">'+
                        '<option value=""></option>'+option_value+'</select>'+'</form>'+'<div id="subcategory">'+'</div>';
                    $('#category-div').html(html);
                }

                $('#category-div').on('click','#category_id',function () {
                    let id = document.getElementById("category_id").value;
                    console.log(id);
                    findSubcategory(id);
                });
                function findSubcategory(id) {
                    $.ajax({
                        url: '{{route('findSubcategory')}}',
                        method: 'GET',
                        data: {
                            '_token': '{{csrf_token()}}',
                            'id': id
                        }
                    }).done(function (data) {
                        console.log(data);
                        addSubcategory(data.subcategories);
                    }).fail(function (error) {
                        console.log(error);
                    });
                }
                function addSubcategory(subCategories) {
                    let option_value = '';
                    let html;
                    for(let i=0 ; i<subCategories.length;i++) {
                        option_value+='<option value="'+subCategories[i].id+'">'+ subCategories[i].name +'</option>';
                    }
                    html = '<label>Subcategory</label>'+
                        '<select name="subcategory_id" class="form-control select2-multiple" id="subcategory_id">'+
                        '<option value=""></option>'+option_value+
                        '</select>';
                    $('#subcategory-div').html(html);
                }
                $('#filter-div').on('click','#filter-button',function () {
                    getFilterValues();
                })
                function getFilterValues() {
                    let category = document.getElementById("category_id");
                    let categoryId=null;
                    if(category){
                        categoryId=category.value;
                    }
                    let subcategory = document.getElementById("subcategory_id");
                    let subcategoryId = null;
                    if(subcategory){
                        subcategoryId = subcategory.value;
                    }
                    let priceMin=$('#price-slider-range-value1');
                    let priceMax = $('#price-slider-range-value2');
                    let priceValue=[];
                    priceValue[0] = null;
                    priceValue[1] = null;
                    if((priceMin) && (priceMax)){
                        priceValue[0] = priceMin.html();
                        priceValue[1] = priceMax.html();
                    }
                    let quantityMin=$('#quantity-slider-range-value1');
                    let quantityMax = $('#quantity-slider-range-value2');
                    let quantityValue=[];
                    if((quantityMin) && (quantityMax)){
                        quantityValue[0] = quantityMin.html();
                        quantityValue[1] = quantityMax.html();
                    }
                    console.log(categoryId,subcategoryId,priceValue,quantityValue);
                    getFilteredProducts(categoryId,subcategoryId,priceValue,quantityValue);
                }
                function getFilteredProducts(categoryId,subcategoryId,priceValue,quantityValue) {
                    $.ajax({
                        url:'{{route('filterProduct')}}',
                        method: 'GET',
                        data:{
                            '_token': '{{csrf_token()}}',
                            'category' : categoryId,
                            'subcategory' : subcategoryId,
                            'priceRange' : priceValue,
                            'quantityRange' : quantityValue
                        }
                    }).done(function (data) {
                        allVariationsDom(data.filteredProducts);
                        console.log(data);
                    }).fail(function (error) {
                        console.log(error);
                    })
                }
                function allVariationsDom(variations){
                    let html='<tr>'+
                        '<th>Name</th>'+
                        '<th>Quantity</th>'+
                        '<th>Price</th>'+
                        '</tr>';
                    for(let i=0; i<variations.length; i++){
                        html+='<tr>'+
                            '<td>'+variations[i].name+'</td>'+
                            '<td>'+variations[i].quantity+'</td>'+
                            '<td>'+variations[i].price+'</td>'+
                            '</tr>';
                    }
                    $('#variation-table').html(html);
                }
            });
        </script>
    @endsection
