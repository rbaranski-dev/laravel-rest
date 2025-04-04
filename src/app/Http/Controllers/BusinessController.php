<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Http\Requests\StoreBusinessRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateBusinessRequest;
use App\Http\Resources\BuisnessResource;

/**
 * @OA\Info(
 *     title="API Documentation",
 *     version="1.0.0",
 *     description="Opis API dla zarządzania firmami i pracownikami"
 * )
 */
class BusinessController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private \App\Interfaces\BuisnessRepositoryInterface $businessRepository) 
    {
        // Middleware can be applied here if needed
    }

    /**
     * @OA\Get(
     *     path="/api/business",
     *     summary="Pobierz listę firm",
     *     @OA\Response(
     *         response=200,
     *         description="Lista firm"
     *     )
     * )
     */
    public function index()
    {
        try {
            $data = $this->businessRepository->getAllBusinesses();
            if ($data->isEmpty()) {
                return ApiResponse::sendResponse($data, 'No businesses found', 404);
            }
            return ApiResponse::sendResponse(BuisnessResource::collection($data), 'Businesses retrieved successfully', 200);
        } catch (\Exception $e) {
            ApiResponse::throw($e, 'Failed to retrieve businesses');
        }
    }


    /**
     * @OA\Post(
     *     path="/api/business",
     *     summary="Utwórz nową firmę",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Nowa Firma")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Firma utworzona"
     *     )
     * )
     */
    public function store(StoreBusinessRequest $request)
    {
        $data = [
            'name' => $request->name,
            'number' => $request->number,
            'address' => $request->address,
            'city' => $request->city,
            'zip_code' => $request->zip_code,
        ];

        try {
            $business = $this->businessRepository->createBusiness($data);
            if ($business) {
                return ApiResponse::sendResponse(new BuisnessResource($business), 'Business created successfully', 201);
            } else {
                return ApiResponse::sendResponse([], 'Failed to create business', 500);
            }
        } catch (\Exception $e) {
            return ApiResponse::throw($e, 'Failed to create business');
        }
    }

    /**
     * @OA\Get(
     *     path="/api/business/{id}",
     *     summary="Pobierz szczegóły firmy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID firmy"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Szczegóły firmy"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Firma nie znaleziona"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $business = $this->businessRepository->getBusinessById($id);
            if ($business) {
                return ApiResponse::sendResponse(new BuisnessResource($business), 'Business retrieved successfully', 200);
            } else {
                return ApiResponse::sendResponse([], 'Business not found', 404);
            }
        } catch (\Exception $e) {
            ApiResponse::throw($e, 'Failed to find business');
        }        
    }

    /**
     * @OA\Put(
     *     path="/api/business/{id}",
     *     summary="Zaktualizuj firmę",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID firmy"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Zaktualizowana Firma"),
     *             @OA\Property(property="number", type="string", example="123456789"),
     *             @OA\Property(property="address", type="string", example="Ulica 1"),
     *             @OA\Property(property="city", type="string", example="Miasto"),
     *             @OA\Property(property="zip_code", type="string", example="00-000")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Firma zaktualizowana"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Błąd podczas aktualizacji firmy"
     *     )
     * )
     */
    public function update(UpdateBusinessRequest $request, $id)
    {
        $data = $request->only([
            'name',
            'number',
            'address',
            'city',
            'zip_code'
        ]);

        try {
            $business = $this->businessRepository->updateBusiness($id, $data);
            if ($business) {
                return ApiResponse::sendResponse(new BuisnessResource($business), 'Business updated successfully', 200);
            } else {
                return ApiResponse::sendResponse([], 'Failed to update business', 500);
            }
        } catch (\Exception $e) {
            ApiResponse::throw($e, 'Failed to update business');
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/business/{id}",
     *     summary="Usuń firmę",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID firmy"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Firma usunięta"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $business = $this->businessRepository->deleteBusiness($id);
            if ($business) {
                return ApiResponse::sendResponse([], 'Business deleted successfully', 200);
            } else {
                return ApiResponse::sendResponse([], 'Failed to delete business', 500);
            }
        } catch (\Exception $e) {
            ApiResponse::throw($e, 'Failed to delete business');
        }
    }

    /**
     * @OA\Get(
     *     path="/api/business/{id}/employee",
     *     summary="Pobierz pracowników firmy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID firmy"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista pracowników"
     *     )
     * )
     */
    public function getBusinessEmployees($id)
    {
        try {
            $business = $this->businessRepository->getBusinessEmployees($id);
            if ($business->employees->isEmpty()) { 
                return ApiResponse::sendResponse([], 'No employees found for this business', 404);
            }
            return ApiResponse::sendResponse(new BuisnessResource($business), 'Employees retrieved successfully', 200);
        } catch (\Exception $e) {
            ApiResponse::throw($e, 'Failed to retrieve employees');
        }
    }

    /**
     * @OA\Post(
     *     path="/api/business/{id}/employee",
     *     summary="Dodaj nowego pracownika do firmy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID firmy"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "surname", "email"},
     *             @OA\Property(property="name", type="string", example="Jan"),
     *             @OA\Property(property="surname", type="string", example="Kowalski"),
     *             @OA\Property(property="email", type="string", example="jan.kowalski@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pracownik dodany"
     *     )
     * )
     */
    public function storeBusinessEmployee(StoreEmployeeRequest $request, $id)
    {
        $data = [
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        try {
            $buisness = $this->businessRepository->createBusinessEmployee($id, $data);
            if ($buisness) {
                return ApiResponse::sendResponse(new BuisnessResource($buisness), 'Employee created successfully', 201);
            } else {
                return ApiResponse::sendResponse([], 'Failed to create employee', 500);
            }
        } catch (\Exception $e) {
            ApiResponse::throw($e, 'Failed to create employee');
        }
    }

    /**
     * @OA\Post(
     *     path="/api/business/{businessId}/employee/{employeeId}",
     *     summary="Dodaj istniejącego pracownika do firmy",
     *     @OA\Parameter(
     *         name="businessId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID firmy"
     *     ),
     *     @OA\Parameter(
     *         name="employeeId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID pracownika"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pracownik dodany do firmy"
     *     )
     * )
     */
    public function addEmployeeToBusiness($businessId, $employeeId)
    {
        try {
            $buisness = $this->businessRepository->createBusinessEmployment($businessId, $employeeId);
            if ($buisness) {
                return ApiResponse::sendResponse(new BuisnessResource($buisness), 'Employee added to business successfully', 200);
            } else {
                return ApiResponse::sendResponse([], 'Failed to add employee to business', 500);
            }
        } catch (\Exception $e) {
            ApiResponse::throw($e, 'Failed to add employee to business');
        }
    }
    
    /**
     * @OA\Delete(
     *     path="/api/business/{businessId}/employee/{employeeId}",
     *     summary="Usuń pracownika z firmy",
     *     @OA\Parameter(
     *         name="businessId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID firmy"
     *     ),
     *     @OA\Parameter(
     *         name="employeeId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID pracownika"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pracownik usunięty z firmy"
     *     )
     * )
     */
    public function removeEmployeeFromBusiness($businessId, $employeeId)    
    {
        try {
            $business = $this->businessRepository->removeEmployeeFromBusiness($businessId, $employeeId);
            if ($business) {
                return ApiResponse::sendResponse(new BuisnessResource($business), 'Employee removed from business successfully', 200);
            } else {
                return ApiResponse::sendResponse([], 'Failed to remove employee from business', 500);
            }
        } catch (\Exception $e) {
            ApiResponse::throw($e, 'Failed to remove employee from business');
        }
    }

}
