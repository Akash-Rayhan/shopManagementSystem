@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6 offset-sm-3 col-md-6 offset-md-3">

                <ul class="nav justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('home')}}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('addCategory')}}">Add Category</a>
                    </li>
                </ul>

            </div>
        </div>
    </div>
    <div>

    <div>

        <h1>Create SubCategory</h1>

        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form method="post">
            @csrf
            <label>Category Name</label>
            <select name="category_id" class="form-control select2-multiple">
                <option value=""></option>
                @foreach($categories as $category)
                    <option value="{{$category->id}}">
                        {{$category->name}}
                    </option>
                @endforeach
            </select>
            <br>
            <label>SubCategory Name</label>
            <br>
            <input type="text" name="name" value="">
            <br>
            <label>Description</label>
            <br>
            <textarea type="text" name="description" ></textarea>
            <br>
            <button type="submit">Save</button>
        </form>
    </div>
@endsection
