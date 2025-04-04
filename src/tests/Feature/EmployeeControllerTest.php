<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Employee;

class EmployeeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_employee()
    {
        $data = [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '123456789',
        ];

        $response = $this->postJson('/api/employee', $data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => ['id', 'name', 'surname', 'email', 'phone'],
            'message',
            'success'
        ]);
        $response->assertJsonFragment($data);
    }

    /** @test */
    public function it_can_get_all_employees()
    {
        $employee = Employee::factory()->create();

        $response = $this->getJson('/api/employee');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'surname', 'email', 'phone']
            ],
            'message',
            'success'
        ]);
    }

    /** @test */
    public function it_can_show_a_single_employee()
    {
        $employee = Employee::factory()->create();

        $response = $this->getJson("/api/employee/{$employee->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => ['id', 'name', 'surname', 'email', 'phone'],
            'message',
            'success'
        ]);
        $response->assertJsonFragment(['id' => $employee->id]);
    }

    /** @test */
    public function it_returns_404_when_employee_not_found()
    {
        $response = $this->getJson('/api/employee/999999');

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Employee not found',
        ]);
    }

    /** @test */
    public function it_can_update_an_employee()
    {
        $employee = Employee::factory()->create();

        $data = [
            'name' => 'Updated Name',
            'surname' => 'Updated Surname',
            'email' => 'updated.email@example.com',
            'phone' => '987654321',
        ];

        $response = $this->putJson("/api/employee/{$employee->id}", $data);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => ['id', 'name', 'surname', 'email', 'phone'],
            'message',
            'success'
        ]);
        $response->assertJsonFragment($data);
    }

    /** @test */
    public function it_can_delete_an_employee()
    {
        $employee = Employee::factory()->create();

        $response = $this->deleteJson("/api/employee/{$employee->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Employee deleted successfully',
        ]);
    }
}
