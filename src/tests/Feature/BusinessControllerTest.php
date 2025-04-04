<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Business;
use App\Models\Employee;

class BusinessControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_business()
    {
        $data = [
            'name' => 'New Business',
            'number' => '12345',
            'address' => '123 Business St',
            'city' => 'Business City',
            'zip_code' => '12345',
        ];

        $response = $this->postJson("/api/business", $data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'number',
                'address',
                'city',
                'zip_code'
            ]
        ]);
        $response->assertJsonFragment($data);
    }

    /** @test */
    public function it_can_get_all_businesses()
    {
        $business = Business::factory()->create();

        $response = $this->getJson("/api/business");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'number', 'address', 'city', 'zip_code']
            ]
        ]);
    }

    /** @test */
    public function it_can_show_a_single_business()
    {
        $business = Business::factory()->create();

        $response = $this->getJson("/api/business/{$business->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => ['id', 'name', 'number', 'address', 'city', 'zip_code']
        ]);
        $response->assertJsonFragment(['id' => $business->id]);
    }

    /** @test */
    public function it_returns_404_when_business_not_found()
    {
        $response = $this->getJson("/api/business/999999");

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Business not found',
        ]);
    }

    /** @test */
    public function it_can_update_a_business()
    {
        $business = Business::factory()->create();

        $data = [
            'name' => 'Updated Business',
            'number' => '67890',
            'address' => '456 Updated St',
            'city' => 'Updated City',
            'zip_code' => '67890',
        ];

        $response = $this->putJson("/api/business/{$business->id}", $data);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => ['id', 'name', 'number', 'address', 'city', 'zip_code']
        ]);
        $response->assertJsonFragment($data);
    }

    /** @test */
    public function it_can_delete_a_business()
    {
        $business = Business::factory()->create();

        $response = $this->deleteJson("/api/business/{$business->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Business deleted successfully',
        ]);
    }

    /** @test */
    public function it_can_get_business_employees()
    {
        $business = Business::factory()->create();
        $employee = Employee::factory()->create();
        $business->employees()->attach($employee);

        $response = $this->getJson("/api/business/{$business->id}/employee");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'number',
                'address',
                'city',
                'zip_code',
                'employees' => [
                    '*' => ['id', 'name', 'surname', 'email', 'phone']
                ]
            ]
        ]);
    }

    /** @test */
    public function it_returns_404_when_no_employees_found_for_business()
    {
        $business = Business::factory()->create();

        $response = $this->getJson("/api/business/{$business->id}/employee");

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'No employees found for this business',
        ]);
    }

    /** @test */
    public function it_can_store_a_business_employee()
    {
        $business = Business::factory()->create();
        $employeeData = [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '123456789',
        ];

        $response = $this->postJson("/api/business/{$business->id}/employee", $employeeData);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'number',
                'address',
                'city',
                'zip_code',
                'employees' => [
                    '*' => ['id', 'name', 'surname', 'email', 'phone']
                ]
            ],
            'message',
            'success'
        ]);
        $response->assertJsonFragment([
            'data' => [
                'id' => $business->id,
                'name' => $business->name,
                'number' => $business->number,
                'address' => $business->address,
                'city' => $business->city,
                'zip_code' => $business->zip_code,
                'employees' => [
                    [
                        'id' => Employee::where('email', $employeeData['email'])->first()->id,
                        'name' => $employeeData['name'],
                        'surname' => $employeeData['surname'],
                        'email' => $employeeData['email'],
                        'phone' => $employeeData['phone'],
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function it_can_add_an_existing_employee_to_business()
    {
        $business = Business::factory()->create();
        $employee = Employee::factory()->create();

        $response = $this->postJson("/api/business/{$business->id}/employee/{$employee->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Employee added to business successfully',
        ]);
    }

    /** @test */
    public function it_can_remove_an_employee_from_business()
    {
        $business = Business::factory()->create();
        $employee = Employee::factory()->create();
        $business->employees()->attach($employee);

        $response = $this->deleteJson("/api/business/{$business->id}/employee/{$employee->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Employee removed from business successfully',
        ]);
    }
}
