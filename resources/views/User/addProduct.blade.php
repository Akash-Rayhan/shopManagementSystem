@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <ul class="nav justify-content-center">
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('home')}}">Home</a>
                        </li>

                    </ul>
                </div>
                <!-- Product Form -->


                    <!-- Product Form -->


                <div class="container">
                    <div class="alert alert-danger" style="display:none" id="error-message">
                    <ul></ul>
                    </div>
                    <div class="alert alert-success" style="display:none" id="success-message">
                        <ul></ul>
                    </div>
                </div>
                <!-- Products List -->
                <div class="card">
                    <div class="card-header" >Products
                        <button type="button" id="addProduct"><i class="fa fa-plus" aria-hidden="true" ></i></button>
                    </div>


                    <div class="card-body row  m-0 col-md-12">
                        <div class="card  col-md-7">
                            <div class="card-header m-0"></div>
                            <div class="card-body" id="product_div">

                            </div>
                        </div>
                        <!-- Product Variations -->
                        <div class="card col-md-5">
                            <div class="card-header">Variations</div>
                            <div class="card-body" id="variation-dom">

                            </div>
                        </div>
                    </div>


                    <!-- Modal -->
                    <div class="modal fade" id="product-edit-form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="product-name">Product name</label>
                                        <input type="text" name="product-name" id="product-name" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="category">Category</label>
                                        <select name="category" id="category" class="form-control">

                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="subcategory">Subcategory</label>
                                        <select name="subcategory" id="subcategory" class="form-control">

                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" id="product-edit-form-submit" data-dismiss="modal">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            getProducts();
            $('#product_div').on('click','#product_name',function () {
               let id = $(this).data('id');
                getVariations(id);
            });
            $('#product_div').on('click','.delete',function () {
                let id = $(this).data('id');
                deleteProduct(id);
            });
            function deleteProduct(id){
                $.ajax({
                    url: '{{route('deleteProduct')}}',
                    method: 'POST',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'id': id
                    }
                }).done(function (data) {
                    console.log(data);
                    getProducts();
                }).fail(function (error) {
                    console.log(error);
                })
            }
            function getVariations(id){
                $.ajax({
                    url : '{{route('getVariations')}}',
                    method: 'GET',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'id': id
                    }
                }).done(function (data) {

                    allVariationsDom(data.variations);
                })
            }
            function allVariationsDom(variations){
                let html='<table width="100%">\n' +
                    '\n' +
                    '\n' +
                    '<tr>'+
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
                html+='</table>';
                $('#variation-dom').html(html);
            }
            $('#product_div').on('click','.edit_modal',function () {
                editId = $(this).data('id');
            });

            $('#addProduct').on('click',function () {
                getCategories();
            });
            $('#product_div').on('click','#category_id',function (e) {
                e.preventDefault();
                let id = document.getElementById("category_id").value;
                console.log(id);
                findSubcategory(id);
            });
            $('#product_div').on('click','#saveProduct',function () {
                let productName = $('#productName').val();
                let category_id = document.getElementById("category_id").value;
                let subcategory_id = document.getElementById("subcategory_id").value;
                saveProduct(productName,subcategory_id,category_id)
            })
            function getProducts() {
                $.ajax({
                    url: '{{route('getProducts')}}',
                    method:'GET',
                }).done(function (data) {
                    console.log(data);
                    getProductsDom(data.products);
                }).fail(function (error) {
                    console.log(error)
                });
            }
            function getProductsDom(products) {
                let html = '';
                for(let i=0 ; i<products.length;i++) {
                    html += '<li>'+
                        '<div class="row">'+
                        '<div class="col-md-4" id="product_name" data-id="'+products[i].id+'">'+
                        products[i].name+
                        '</div>'+
                        '<div class="col-md-2">'+
                        '<button type="button" data-id="'+products[i].id+'" data-name="'+products[i].name+'" data-category="'+products[i].category_id+'" data-subcategory="'+products[i].subcategory_id+'" id="product-edit-button" class="btn edit_modal" data-toggle="modal" data-target="#product-edit-form"  rel="modal:open"><i class="fa fa-edit" ></i></button>'+
                        '<button data-id="'+products[i].id+'" type="button" class="add"><i class="fa fa-plus" aria-hidden="true"></i></button>'+
                        '<button data-id="'+products[i].id+'" class="delete"><i class="fa fa-trash"></i></button>'+
                        '</div>'+
                        '</div>'+
                        '</li>'+
                        '<hr>';
                }
                $('#product_div').html(html);
            }
            let addProductId;
            $('#product_div').on('click','.add',function () {
                addProductId = $(this).data('id');
                variationDom();
            });
            function variationDom() {

                let html='<label>ProductVariation Name</label>'+
                    '<br>'+
                    '<input type="text" name="name" id="name" value="">'+
                    '<br>'+
                    '<label>Quantity</label>'+
                    '<br>'+
                    '<input type="number" name="quantity" id="quantity" value="">'+
                    '<br>'+
                    '<label>Price</label>'+
                    '<br>'+
                    '<input type="number" name="price" id="price" value="">'+
                    '<br>'+
                    '<button type="submit" id="variation-button">Save</button>';
                $('#variation-dom').html(html);
            }
            $('#variation-dom').on('click','#variation-button',function () {
                let name = $('#variation-dom').find('#name').val();
                let quantity = $('#variation-dom').find('#quantity').val();
                let price = $('#variation-dom').find('#price').val();
                saveVariation(name,quantity,price);
            });
            function saveVariation(name,quantity,price){
                $.ajax({
                    url: '{{route('addVariation')}}',
                    headers: {
                        'X-CSRF-TOKEN': "{{csrf_token()}}",
                        //'Content-Type':'application/json',
                        'accept': "application/json"
                    },
                    method: 'POST',
                    data: {
                        'name': name,
                        'quantity': quantity,
                        'price': price,
                        'product_id': addProductId
                    },
                }).done(function (data) {
                    console.log(addProductId);
                    getVariations(addProductId);
                    if($.isEmptyObject(data.message)){
                        printSuccessMsg(data.success);
                    }else{
                        printErrorMsg(data.message);
                    }

                }).fail(function (error) {
                    console.log(error);
                });
            }

            let editProductId;
            $('#product_div').on('click','#product-edit-button',function () {
                editProductId = $(this).data('id');
                let productName = $(this).data('name');
                let subcategoryId = $(this).data('subcategory');
                let categoryId = $(this).data('category');
                getCategoriesforEdit();
                findSubcategoryforEdit(categoryId,productName,subcategoryId);

            });
            $('#product-edit-form').on('click','#category',function () {
                let categoryId = $(this).val();
                findSubcategoryforUpdate(categoryId);
            })
            $('#product-edit-form').on('click','#product-edit-form-submit',function () {
                let productName = $('#product-name').val();
                let subcategoryId = $('#subcategory').val();
                let categoryId = $('#category').val();
                updateProduct(productName,subcategoryId,categoryId);
            })
            function updateProduct(productName,subcategoryId,categoryId){
                $.ajax({
                    url: '{{route('updateProduct')}}',
                    method: 'POST',
                    data:{
                        '_token' : '{{csrf_token()}}',
                        'id':editProductId,
                        'name':productName,
                        'subcategory_id': subcategoryId,
                        'category_id': categoryId
                    }
                });
            }

            function addProductDom(categories) {
                let option_value='';
                let html;
                for(let i=0 ; i<categories.length;i++) {
                    option_value+='<option value="'+categories[i].id+'" class="option_value">'+ categories[i].name +'</option>';
                }
                html='<form>'+'<label>Product Name</label>'+'<input type="text" name="name" value="" id="productName" >'+'<br>'+
                    '<label>Category Name</label>'+'<select name="category_id" class="form-control select2-multiple" id="category_id">'+
                    '<option value=""></option>'+option_value+'</select>'+'</form>'+'<div id="subcategory">'+'</div>';
                $('#product_div').html(html);
            }

            function getCategories() {
                $.ajax({
                    url: '{{route('getCategories')}}',
                    method: 'GET',
                }).done(function (data) {
                    console.log(data);
                    addProductDom(data.categories);
                }).fail(function (error) {
                    console.log(error);
                });
            }
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
                    '</select>'+
                    '<br>'+
                    '<button id="saveProduct" type="submit">Save</button>';
                $('#subcategory').html(html);
            }
            function saveProduct(productName,subcategory_id,category_id){
                $.ajax({
                    url: '{{route('storeProduct')}}',
                    headers: {
                        'X-CSRF-TOKEN':"{{csrf_token()}}",
                        //'Content-Type':'application/json',
                        'accept':"application/json"
                    },
                    method: 'POST',
                    data:{
                        'name':productName,
                        'subcategory_id': subcategory_id,
                        'category_id': category_id
                    },
                }).done(function (data) {
                    console.log(data.message);
                    if($.isEmptyObject(data.message)){
                        printSuccessMsg(data.success);
                    }else{
                        printErrorMsg(data.message);
                    }
                }).fail(function (error) {
                    console.log(error);
                });
            }
            function getCategoriesforEdit(){
                $.ajax({
                    url: '{{route('getCategories')}}',
                    method: 'GET',
                }).done(function (data) {
                    console.log(data);
                    categoryOptions(data.categories);
                }).fail(function (error) {
                    console.log(error);
                });
            }
            function categoryOptions(categories) {
                let option_value = '';
                for(let i=0 ; i<categories.length;i++) {
                    option_value+='<option value="'+categories[i].id+'" class="option_value">'+ categories[i].name +'</option>';
                }
                $('#product-edit-form').find('#category').html(option_value);
            }
            function findSubcategoryforEdit(categoryId,productName,subcategoryId) {
                $.ajax({
                    url: '{{route('findSubcategory')}}',
                    method: 'GET',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'id': categoryId,
                    }
                }).done(function (data) {
                    console.log(data);
                    addSubcategoryforEdit(data.subcategories);
                    setProductEditFormData(productName,categoryId,subcategoryId);
                }).fail(function (error) {
                    console.log(error);
                });
            }
            function addSubcategoryforEdit(subCategories) {
                let option_value = '';
                for(let i=0 ; i<subCategories.length;i++) {
                    option_value+='<option value="'+subCategories[i].id+'">'+ subCategories[i].name +'</option>';
                }
                $('#product-edit-form').find('#subcategory').html(option_value);
            }
            function setProductEditFormData(productName,categoryId,subcategoryId) {
                $('#product-edit-form').find('#product-name').val(productName);
                $('#product-edit-form').find('#category').val(categoryId);
                $('#product-edit-form').find('#subcategory').val(subcategoryId);

            }

            function findSubcategoryforUpdate(categoryId) {
                $.ajax({
                    url: '{{route('findSubcategory')}}',
                    method: 'GET',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'id': categoryId,
                    }
                }).done(function (data) {
                    console.log(data);
                    addSubcategoryforUpdate(data.subcategories);
                }).fail(function (error) {
                    console.log(error);
                });
            }

            function addSubcategoryforUpdate(subCategories) {
                let option_value = '';
                for(let i=0 ; i<subCategories.length;i++) {
                    option_value+='<option value="'+subCategories[i].id+'">'+ subCategories[i].name +'</option>';
                }
                $('#product-edit-form').find('#subcategory').html(option_value);
            }

            function printErrorMsg (msg) {
                $("#error-message").find("ul").html('');
                $("#error-message").css('display', 'block');
                    $("#error-message").find("ul").append('<li>' + msg + '</li>');

            }
            function printSuccessMsg (msg) {
                $("#success-message").find("ul").html('');
                $("#success-message").css('display', 'block');
                $("#success-message").find("ul").append('<li>' + msg + '</li>');

            }


        });

    </script>


@endsection
