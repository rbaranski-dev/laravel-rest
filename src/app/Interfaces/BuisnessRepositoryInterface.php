<?php

namespace App\Interfaces;

interface BuisnessRepositoryInterface
{
    public function getAllBusinesses();

    public function getBusinessById($id);

    public function createBusiness(array $data);

    public function updateBusiness($id, array $data);

    public function deleteBusiness($id);

    public function getBusinessEmployees($id);

    public function createBusinessEmployment($businessId, $employeeId);

    public function createBusinessEmployee($businessId, array $data);

    public function removeEmployeeFromBusiness($businessId, $employeeId);
}
