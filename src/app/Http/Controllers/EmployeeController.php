<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEemployeeRequest;
use App\Models\Employee;
use App\Classes\ApiResponse; 
use App\Http\Resources\EmployeeResource;

class EmployeeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private \App\Interfaces\EmployeeRepositoryInterface $employeeRepository)
    {
        // Middleware can be applied here if needed
    }

    /**
     * @OA\Get(
     *     path="/api/employee",
     *     summary="Pobierz listę pracowników",
     *     @OA\Response(
     *         response=200,
     *         description="Lista pracowników"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nie znaleziono pracowników"
     *     )
     * )
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     * 
     */
    public function index()
    {

        try {
            $data = $this->employeeRepository->getAllEmployees();
            if ($data->isEmpty()) {
                return ApiResponse::sendResponse($data, 'No employees found', 404);
            }
            return ApiResponse::sendResponse(EmployeeResource::collection($data), 'Employees retrieved successfully', 200);
        } catch (\Exception $e) {
            return ApiResponse::throw($e, 'Failed to retrieve employees');
        }

    }

    /**
     * @OA\Post(
     *     path="/api/employee",
     *     summary="Dodaj nowego pracownika",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "surname", "email"},
     *             @OA\Property(property="name", type="string", example="Jan"),
     *             @OA\Property(property="surname", type="string", example="Kowalski"),
     *             @OA\Property(property="email", type="string", example="jan.kowalski@example.com"),
     *             @OA\Property(property="phone", type="string", example="123456789")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pracownik dodany"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Błąd podczas dodawania pracownika"
     *     )
     * )
     * Store a newly created resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeeRequest $request)
    {

        $data = [
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        try {
            $employee = $this->employeeRepository->createEmployee($data);
            if ($employee) {
                return ApiResponse::sendResponse(new EmployeeResource($employee), 'Employee created successfully', 201);
            } else {
                return ApiResponse::sendResponse([], 'Failed to create employee', 500);
            }
        } catch (\Exception $e) {
            ApiResponse::throw($e, 'Failed to create employee');
        }
    }

    /**
     * @OA\Get(
     *     path="/api/employee/{id}",
     *     summary="Pobierz szczegóły pracownika",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID pracownika"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Szczegóły pracownika"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pracownik nie znaleziony"
     *     )
     * )
     * Display the specified resource.
     * 
     * @param  int  $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $employee = $this->employeeRepository->getEmployeeById($id);
            if ($employee) {
                return ApiResponse::sendResponse(new EmployeeResource($employee), 'Employee retrieved successfully', 200);
            } else {
                return ApiResponse::sendResponse([], 'Employee not found', 404);
            }
        } catch (\Exception $e) {
            ApiResponse::throw($e, 'Failed to retrieve employee');
        }
    }


    /**
     * @OA\Put(
     *     path="/api/employee/{id}",
     *     summary="Zaktualizuj dane pracownika",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID pracownika"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Jan"),
     *             @OA\Property(property="surname", type="string", example="Kowalski"),
     *             @OA\Property(property="email", type="string", example="jan.kowalski@example.com"),
     *             @OA\Property(property="phone", type="string", example="123456789")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dane pracownika zaktualizowane"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Błąd podczas aktualizacji danych pracownika"
     *     )
     * )
     * Update the specified resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEemployeeRequest $request, $id)
    {
        $data = $request->only(['name', 'surname', 'email', 'phone']);

        try {
            $employee = $this->employeeRepository->updateEmployee($id, $data);
            if ($employee) {
                return ApiResponse::sendResponse(new EmployeeResource($employee), 'Employee updated successfully', 200);
            } else {
                return ApiResponse::sendResponse([], 'Failed to update employee', 500);
            }
        } catch (\Exception $e) {
            ApiResponse::throw($e, 'Failed to update employee');
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/employee/{id}",
     *     summary="Usuń pracownika",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID pracownika"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pracownik usunięty"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Błąd podczas usuwania pracownika"
     *     )
     * )
     * Remove the specified resource from storage.
     * 
     * @param  int  $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $deleted = $this->employeeRepository->deleteEmployee($id);
            if ($deleted) {
                return ApiResponse::sendResponse([], 'Employee deleted successfully', 200);
            } else {
                return ApiResponse::sendResponse([], 'Failed to delete employee', 500);
            }
        } catch (\Exception $e) {
            ApiResponse::throw($e, 'Failed to delete employee');
        }
    }
}
