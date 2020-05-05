<?php


namespace App\Services;


use App\Exports\EmployeeExport;
use App\Model\Employee;
use App\Model\Shop;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class EmployeeService
{
    /**
     * @param $request
     * @return array|bool[]
     */
    public function createEmployee($request){
        try {
            $userShop = Shop::where('user_id', Auth::id())->first();
            $shopId = $userShop->id;
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:50',
                'salary'=>'required'
            ]);

            if ($validator->passes()) {
                Employee::create([
                    'name' => $request->name,
                    'salary' => $request->salary,
                    'shop_id' => $shopId,
                    'still_working' => true,
                    'started_at' => Carbon::now()->format('Y-m-d'),
                    'ended_at' => null
                ]);

                return ['status' => true];
            }

            return ['error'=>$validator->errors()->all()];
        }catch (\Exception $e){
            return ['status'=> false, 'message'=> $e->getMessage()];
        }

    }

    /**
     * @return array
     */
    public function allEmployeeInformation(){
        try {
            $userShop = Shop::where('user_id', Auth::id())->first();
            $shopId = $userShop->id;
            $employees=Employee::where('shop_id', $shopId)->get();

            return ['employees' => $employees, 'status' => true];
        }catch (\Exception $e){
            return ['status'=> false, 'message'=> $e->getMessage()];
        }

    }

    /**
     * @param $request
     * @return array
     */
    public function saveEditInformation($request){
        try {
            $still_working = $request->ended_at <= Carbon::now();
            Employee::find($request->id)->update([
                'name' => $request->name,
                'salary' => $request->salary,
                'still_working' => $still_working,
                'started_at' => Carbon::now()->format('Y-m-d'),
                'ended_at'=> $request->ended_at
            ]);

            return ['success'=> 'Updated successfully','status'=> true];
        }catch (\Exception $e){
            return ['status'=> false, 'message' => $e->getMessage()];
        }

    }

    /**
     * @param $request
     * @return array
     */
    public function deleteEmployee($request){
        try {
            Employee::find($request->id)->delete();
        }catch (\Exception $e){
            return['message'=>$e->getMessage(),'status'=> false];
        }

    }

    /**
     * @param $request
     * @param $employee
     * @return array|bool[]
     */
    public function filterEmployees($request, $employee){
        try {
            $salaryRange=$request->salary;
            $started_at = $request->started_at;
            $userShop = Shop::where('user_id', Auth::id())->first();
            $shopId = $userShop->id;
            $filteredEmployees = null;
            $employee = $employee->newQuery();
            if(($salaryRange) || ($started_at)){
                if (($salaryRange)){
                    $employee->whereBetween('salary',$salaryRange);
                }
                if(($started_at)){
                    $employee->whereBetween('started_at',$started_at);
                }
                $filteredEmployees = $employee->where('shop_id',$shopId)->get();
            }
            return ['employees'=> $filteredEmployees,'status'=> true];
        }catch (\Exception $e){
            return ['status'=> false,'message'=> $e->getMessage()];
        }
    }

    /**
     * @param $request
     * @param $employee
     * @return array
     */
    public function searchEmployeeName($request, $employee){
        try {
            $userShop = Shop::where('user_id', Auth::id())->first();
            $shopId = $userShop->id;
            $name = $request->name;
            $searchedEmployee=null;
            $employee = $employee->newQuery();
            if(($name)){
                $employee->where('shop_id',$shopId);
                $searchedEmployee = $employee->where('name','like',"%{$name}%")->get();
            }
            return['employees'=> $searchedEmployee,'status'=>true];
        }catch(\Exception $e){
            return ['status'=> false,'message'=> $e->getMessage()];
        }
    }

    /**
     * @param $request
     * @return array|BinaryFileResponse
     */
    public function downloadExcel($request){
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
