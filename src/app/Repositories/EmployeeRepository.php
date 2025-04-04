<?php

namespace App\Repositories;

use App\Models\Employee;

class EmployeeRepository implements \App\Interfaces\EmployeeRepositoryInterface
{

    public function getAllEmployees()
    {
        return Employee::all();
    }

    public function getEmployeeById($id)
    {
        return Employee::find($id);
    }

    public function createEmployee(array $data)
    {
        return Employee::create($data);
    }

    public function updateEmployee($id, array $data)
    {
        $employee = Employee::find($id);
        if ($employee) {
            $employee->update($data);
            return $employee;
        }
        return null;
    }

    public function deleteEmployee($id)
    {
       return Employee::destroy($id);
    }

}
