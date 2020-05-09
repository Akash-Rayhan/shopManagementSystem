<?php

namespace App\Http\Controllers;

use App\Exports\EmployeeExport;
use App\Http\Requests\EmployeeRequest;
use App\Model\Employee;
use App\Services\EmployeeService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class EmployeeController extends Controller
{
    protected $employeeService;

    /**
     * EmployeeController constructor.
     * @param EmployeeService $employeeService
     */
    public function __construct(EmployeeService $employeeService){
        $this->employeeService = $employeeService;
    }

    /**
     * @return Application|Factory|View
     */
    public function addEmployee(){

        return view('User.addEmployee');
    }

    /**
     * @return array|JsonResponse
     */
    public function getEmployees(){

        $response = $this->employeeService->allEmployeeInformation();

        return response()->json([
            'status'=> $response['status'],
            'employees'=>$response['employees']
        ]);

    }

    /**
     * @param EmployeeRequest $request
     * @return array|JsonResponse
     */
    public function storeEmployee(EmployeeRequest $request){
        $response = $this->employeeService->createEmployee($request);

        return response()->json([
            'status'=> $response['status'],
            'success'=> 'Employee saved'
        ]);


    }

    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function editEmployee(Request $request){
        $response=$this->employeeService->saveEditInformation($request);

        return response()->json([
            'success'=> $response['success'],
            'status'=> $response['status']
        ]);


    }

    /**
     * @param Request $request
     * @return void
     */
    public function deleteEmployee(Request $request){
            $this->employeeService->deleteEmployee($request);
    }

    /**
     * @return Application|Factory|View
     */
    public function searchEmployee(){
        return view('User.searchEmployee');
    }

    /**
     * @param Request $request
     * @param Employee $employee
     * @return JsonResponse
     */
    public function filterEmployee(Request $request, Employee $employee){
        $response = $this->employeeService->filterEmployees($request,$employee);
        return response()->json([
            'employees'=>$response['employees'],
            'status'=> $response['status']
        ]);
    }

    /**
     * @param Request $request
     * @param Employee $employee
     * @return JsonResponse
     */
    public function searchEmployeeName(Request $request, Employee $employee){
        $response = $this->employeeService->searchEmployeeName($request,$employee);
        return response()->json(['employees'=>$response['employees'],'status'=> $response['status']]);
    }

    /**
     * @param Request $request
     * @return array|BinaryFileResponse
     */
    public function export(Request $request){

        return $this->employeeService->downloadExcel($request);
    }
}
