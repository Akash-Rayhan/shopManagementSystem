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
            </ul>
        </div>
        <div class="card">

            <div class="card-body row  m-0 col-md-12">
                <div class="card  col-md-4">
                    <div class="card-header m-0">Filters</div>
                    <div class="card-body" id="filter-div">

                        <div id="salary-range"></div><br>
                        <span>Salary Range <button id="salary-range-button"><i class="fa fa-plus" aria-hidden="true"></i></button></span>
                        <div id="salary-range-div"></div><br>

                        <span> Started Date <button id="started-date-button"><i class="fa fa-plus" aria-hidden="true"></i></button></span>
                        <div id="started-date-div"></div><br>

                        <button id="filter-button" >Filter</button>
                    </div>
                </div>

                <!-- Product Variations -->
                <div class="card col-md-8">
                    <div class="card-header">
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" placeholder="Employee Name" id="employee-name"><button id="employee-search-button"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                    <div class="card-body" id="employee-dom">
                        <table width="100%" id="employee-table">


                        </table>
                    </div>
                    <form action="{{route('employeeExport')}}" method="get" id="export-form">
                        @csrf
                        <input id="export-input" type="hidden" name="employees" value="">
                        <button class="btn btn-success btn-submit" id="download-excel">Download Excel file</button>
                    </form>
                </div>

            </div>

        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            let date = [];
            $('#salary-range-button').on('click',function () {
                let range = [5000,10000];
                salaryRangeDom(range);
            })
            function salaryRangeDom(range) {
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
                $('#salary-range-div').html(html);
                activatePriceSlider(range);
            }
            $('#started-date-button').on('click',function () {
                startedDateDom();
            })
            function startedDateDom() {
                let html = '<input type="text" name="daterange" value="" />';
                $('#started-date-div').html(html);
                $(function() {
                    $('input[name="daterange"]').daterangepicker({
                        opens: 'left'
                    }, function(start, end, label) {
                        date[0] = start.format('YYYY-MM-DD');
                        date[1] = end.format('YYYY-MM-DD');
                        //console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                    });
                })
            }
            $('#filter-button').on('click',function () {
                let salaryValue = [];
                let salaryMin=$('#price-slider-range-value1');
                let salaryMax = $('#price-slider-range-value2');
                if((salaryMin) && (salaryMax)){
                    salaryValue[0] = salaryMin.html();
                    salaryValue[1] = salaryMax.html();
                }
                console.log(date,salaryValue);
                getFilteredEmployees(date,salaryValue);
            })
            function getFilteredEmployees(date,salaryValue) {
                $.ajax({
                    url:'{{route('filterEmployee')}}',
                    method: 'GET',
                    data:{
                        '_token': '{{csrf_token()}}',
                        'started_at': date,
                        'salary' : salaryValue
                    }
                }).done(function (data) {
                    allEmployeesDom(data.employees);
                    //console.log(data);
                }).fail(function (error) {
                    console.log(error);
                })
            }
            function allEmployeesDom(employees) {
                console.log(employees);
                let html='<tr>'+
                    '<th>Name</th>'+
                    '<th>Salary</th>'+
                    '<th>Started_at</th>'+
                    '</tr>';
                for(let i=0; i<employees.length; i++){
                    html+='<tr>'+
                        '<td>'+employees[i].name+'</td>'+
                        '<td>'+employees[i].salary+'</td>'+
                        '<td>'+employees[i].started_at+'</td>'+
                        '</tr>';
                }
                $('#employee-table').html(html);
                downloadEmployees(employees);
            }
            function downloadEmployees(employees){
                $('#download-excel').on('click',function () {
                    $('#export-input').val(JSON.stringify(employees));
                    $('#export-form').submit();
                })
            }
            $('#employee-search-button').on('click',function () {
                let name = $('#employee-name').val();
                getSearchedEmployee(name);
            })
            function getSearchedEmployee(name) {
                $.ajax({
                    url:'{{route('searchEmployeeName')}}',
                    method: 'GET',
                    data:{
                        '_token': '{{csrf_token()}}',
                        'name': name
                    }
                }).done(function (data) {
                    allEmployeesDom(data.employees);
                    //console.log(data);
                }).fail(function (error) {
                    console.log(error);
                })
            }


        });
    </script>
@endsection
