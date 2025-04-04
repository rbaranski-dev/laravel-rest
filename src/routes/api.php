<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/business', App\Http\Controllers\BusinessController::class);
Route::get('/business/{business}/employee', [App\Http\Controllers\BusinessController::class, 'getBusinessEmployees']);  
Route::post('/business/{business}/employee', [App\Http\Controllers\BusinessController::class, 'storeBusinessEmployee']); 
Route::post('/business/{business}/employee/{employee}', [App\Http\Controllers\BusinessController::class, 'addEmployeeToBusiness']);  
Route::delete('/business/{business}/employee/{employee}', [App\Http\Controllers\BusinessController::class, 'removeEmployeeFromBusiness']); 

Route::apiResource('/employee', App\Http\Controllers\EmployeeController::class);