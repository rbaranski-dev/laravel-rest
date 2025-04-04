<?php

namespace App\Repositories;

use App\Models\Business;
use App\Models\Employee;

class BuisnessRepository implements \App\Interfaces\BuisnessRepositoryInterface
{
    
    public function getAllBusinesses()
    {
        return Business::all();
    }
    
    public function getBusinessById($id)
    {
        return Business::find($id);
    }

    public function createBusiness(array $data)
    {
        return Business::create($data);
    }
    
    public function updateBusiness($id, array $data)
    {
        $buisiness = Business::find($id);
        if ($buisiness->update($data)) {
            return $buisiness;
        }
        return null;
    }
    
    public function deleteBusiness($id)
    {
        return Business::destroy($id);
    }

    public function getBusinessEmployees($id)
    {
        $business = Business::with('employees')->find($id);
        return $business;;
    }
    
    public function createBusinessEmployment($businessId, $employeeId)
    {
        $business = Business::with('employees')->find($businessId);
        $employee = Employee::find($employeeId);

        if ($business && $employee) {
            // Check if the employee is already employed by the business
            $existingEmployment = $business->employees()->where('employee_id', $employeeId)->exists();
            if ($existingEmployment) {
                return $business; // Employee already employed
            }
            $business->employees()->attach($employee);
            return Business::with('employees')->find($businessId);
        }
        return null;
    }

    public function createBusinessEmployee($businessId, array $data)
    {
        $business = Business::find($businessId);
        $employee = Employee::create($data);
        if ($business && $employee) {
            $business->employees()->attach($employee);
            return Business::with('employees')->find($businessId);
        }
        return null;
    }

    public function removeEmployeeFromBusiness($businessId, $employeeId)
    {
        $business = Business::find($businessId);
        if ($business) {
            $business->employees()->detach($employeeId);
        }
        return $business;
    }    
}
