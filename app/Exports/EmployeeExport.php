<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;

class EmployeeExport implements FromArray{
    protected $employees;

    /**
     * EmployeeExport constructor.
     * @param array $employees
     */
    public function __construct($employees){
        $this->employees = $employees;
    }




    public function array(): array
    {
        return $this->employees;
    }
}
