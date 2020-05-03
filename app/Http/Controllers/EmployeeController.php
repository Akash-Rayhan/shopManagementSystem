<?php

namespace App\Http\Controllers;

use App\Exports\EmployeeExport;
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
        try {
            $response = $this->employeeService->allEmployeeInformation();

            return response()->json(['status'=> $response['status'],'employees'=>$response['employees']]);
        }catch(\Exception $e){
            return ['success'=> false,'message'=>[$e->getMessage()]];
        }

    }

    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function storeEmployee(Request $request){
        try {
            $response = $this->employeeService->createEmployee($request);
            if($response['status']){

                return response()->json(['success'=> 'Employee saved','status'=> true]);
            }

            return response()->json(['error'=> $response['error'], 'status'=> false]);
        }catch (\Exception $e){

            return ['success'=> false,'message'=>[$e->getMessage()]];
        }

    }

    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function editEmployee(Request $request){
        try {
            $response=$this->employeeService->saveEditInformation($request);

            return response()->json(['success'=> $response['success'],'status'=> $response['status']]);
        }catch (\Exception $e){

            return ['success'=> false,'message'=>[$e->getMessage()]];
        }

    }

    /**
     * @param Request $request
     * @return array
     */
    public function deleteEmployee(Request $request){
        try {
            $this->employeeService->deleteEmployee($request);
        }catch(\Exception $e){

            return ['success'=> false,'message'=>[$e->getMessage()]];
        }

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
        return response()->json(['employees'=>$response['employees'],'status'=> $response['status']]);
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
        try {
            $employees = json_decode($request->employees);
            if(($employees)){

                return Excel::download(new EmployeeExport($employees),'employeesList.xlsx');
            }
        }catch (\Exception $e){

            return ['status'=> false,'message'=> $e->getMessage()];
        }

    }
}
