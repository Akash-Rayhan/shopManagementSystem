@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6 offset-sm-3 col-md-6 offset-md-3">

                <ul class="nav justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('addCategory')}}">Add Category</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('addSubCategory')}}">Add SubCategory</a>
                    </li>
                </ul>

            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        admin
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
