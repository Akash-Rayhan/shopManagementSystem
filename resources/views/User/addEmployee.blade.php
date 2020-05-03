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

                <div class="container">
                    <div class="alert alert-danger print-error-msg" style="display:none">
                        <ul></ul>
                    </div>

                </div>
                <!-- Products List -->
                <div class="card">
                    <div class="card-header" >Employees

                    </div>
                    <div class="card-body row  m-0 col-md-12">
                        <div class="card  col-md-7">
                            <div class="card-header m-0"></div>
                            <div class="card-body" id="employee_div">
                                <table width="100%" id="employee-table">


                                </table>
                            </div>
                        </div>
                            <div class="card col-md-5">
                                <div class="card-header">Add Employee</div>
                                <div class="card-body">
                                    <form>
                                        <div class="form-group">
                                            <label>Employee Name:</label>
                                            <input type="text" name="name" class="form-control" placeholder="Employee Name" id="name">
                                        </div>
                                        <div class="form-group">
                                            <label>Employee Salary:</label>
                                            <input type="number" name="salary" class="form-control" placeholder="Employee Salary" id="salary">
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-success btn-submit" id="addEmployee">Submit</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                    </div>
                </div>
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
                                            <input type="text" name="employee-name" id="employee-name" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="salary">Salary</label>
                                            <input type="number" id="employee-salary" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="started_at">Started_at</label>
                                            <input type="date" id="started_at" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="ended_at">Ended_at</label>
                                            <input type="date" id="ended_at" class="form-control">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" id="employee-edit-form-submit" data-dismiss="modal">Save changes</button>
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
            let editId;
            getEmployees();
            $('#addEmployee').on('click',function () {
                let name=$('#name').val();
                let salary=$('#salary').val();
                saveEmployee(name,salary);
            });
            $('#employee-table').on('click','#employee-edit-button',function () {
                editId = $(this).data('id');
                let name = $(this).data('name');
                let salary = $(this).data('salary');
                let started_at = $(this).data('started_at');
                let ended_at = $(this).data('ended_at');
                employeeInformationModal(name,salary,started_at,ended_at);
            });
            $('#employee-edit-form-submit').on('click',function () {
                let name = $('#employee-name').val();
                let salary = $('#employee-salary').val();
                let started_at = $('#started_at').val();
                let ended_at = $('#ended_at').val();
                saveEditInformation(name,salary,started_at,ended_at);
            });
            function employeeInformationModal(name,salary,started_at,ended_at) {
                $('#employee-name').val(name);
                $('#employee-salary').val(salary);
                $('#started_at').val(started_at);
                $('#ended_at').val(ended_at);
            }
            function getEmployees() {
                $.ajax({
                    url : '{{route('getEmployees')}}',
                    method: 'GET'
                }).done(function (data) {
                    console.log(data);
                    getEmployeesDom(data.employees);
                }).fail(function (error) {
                    console.log(error);
                })
            }
            function getEmployeesDom(employees) {
                let status=[];
                let html='<tr>'+
                    '<th>Name</th>'+
                    '<th>Salary</th>'+
                    '<th>Status</th>'+
                    '</tr>';

                for(let i=0; i<employees.length;i++){
                    console.log(employees[i].still_working);
                    if(employees[i].still_working === 1){
                        status[i]="active";
                    }
                    else{
                        status[i]="inactive";
                    }
                    html+='<tr>'+
                        '<td>'+employees[i].name+'</td>'+
                        '<td>'+employees[i].salary+'</td>'+
                        '<td>'+status[i]+'</td>'+
                        '<td>'+'<button data-id="'+employees[i].id+'" data-name="'+employees[i].name+'" data-salary="'+employees[i].salary+'" data-started_at="'+employees[i].started_at+'" data-ended_at="'+employees[i].ended_at+'" type="button" id="employee-edit-button" class="btn edit_modal" data-toggle="modal" data-target="#product-edit-form"  rel="modal:open"><i class="fa fa-edit" ></i></button>'+'</td>'+
                        '<td>'+'<button data-id="'+employees[i].id+'" class="delete"><i class="fa fa-trash"></i></button>'+'</td>'+
                        '</tr>';

                }
                $('#employee-table').html(html);

            }
            $('#employee-table').on('click','.delete',function () {
                let id = $(this).data('id');
                deleteEmployee(id);
            });
            function deleteEmployee(id){
                $.ajax({
                    url: '{{route('deleteEmployee')}}',
                    method: 'POST',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'id': id
                    }
                }).done(function (data) {
                    console.log(data);
                    getEmployees();
                }).fail(function (error) {
                    console.log(error);
                })
            }
            function saveEmployee(name,salary) {
                $.ajax({
                    url: '{{route('storeEmployee')}}',
                    type: "POST",
                    data: {
                        '_token': '{{csrf_token()}}',
                        'name': name,
                        'salary': salary,
                    },
                    success: function(data) {
                        if($.isEmptyObject(data.error)){
                            alert(data.success);
                            console.log(data);
                        }else{
                            printErrorMsg(data.error);
                            console.log(data);
                        }
                    }
                });
            }


            function saveEditInformation(name,salary,started_at,ended_at) {
                $.ajax({
                    url: '{{route('editEmployee')}}' ,
                    type: "POST",
                    data: {
                        '_token': '{{csrf_token()}}',
                        'id':editId,
                        'name':name,
                        'salary': salary,
                        'started_at':started_at,
                        'ended_at': ended_at
                    }

                }).done(function (data) {
                    alert(data.success);
                    getEmployees();
                })

            }
            function printErrorMsg (msg) {
                $(".print-error-msg").find("ul").html('');
                $(".print-error-msg").css('display', 'block');
                $.each(msg, function (key, value) {
                    $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
                });
            }
        });

    </script>

    @endsection
