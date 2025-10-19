<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function employee_can_access_curriculum_export_tool()
    {
        // Create an employee user
        $employee = User::factory()->create([
            'role' => 'employee',
            'username' => 'testemployee',
            'email' => 'employee@test.com'
        ]);

        // Act as the employee
        $response = $this->actingAs($employee)
            ->get('/curriculum_export_tool');

        // Should be able to access
        $response->assertStatus(200);
    }

    /** @test */
    public function employee_cannot_access_admin_routes()
    {
        // Create an employee user
        $employee = User::factory()->create([
            'role' => 'employee',
            'username' => 'testemployee',
            'email' => 'employee@test.com'
        ]);

        // Test admin-only routes that should be forbidden
        $adminRoutes = [
            '/curriculum_builder',
            '/course-builder',
            '/subject_mapping',
            '/pre_requisite',
            '/grade-setup',
            '/equivalency_tool',
            '/compliance-validator',
            '/employees'
        ];

        foreach ($adminRoutes as $route) {
            $response = $this->actingAs($employee)->get($route);
            
            // Should be forbidden (403) or redirect to login
            $this->assertTrue(
                $response->status() === 403 || $response->status() === 302,
                "Employee should not access {$route}, got status: " . $response->status()
            );
        }
    }

    /** @test */
    public function employee_redirected_from_dashboard_to_export_tool()
    {
        // Create an employee user
        $employee = User::factory()->create([
            'role' => 'employee',
            'username' => 'testemployee',
            'email' => 'employee@test.com'
        ]);

        // Access dashboard
        $response = $this->actingAs($employee)->get('/');

        // Should redirect to curriculum export tool
        $response->assertRedirect('/curriculum_export_tool');
    }

    /** @test */
    public function admin_can_access_all_routes()
    {
        // Create an admin user
        $admin = User::factory()->create([
            'role' => 'admin',
            'username' => 'testadmin',
            'email' => 'admin@test.com'
        ]);

        // Test that admin can access admin routes
        $adminRoutes = [
            '/' => 200, // Dashboard
            '/curriculum_export_tool' => 200,
            '/employees' => 200
        ];

        foreach ($adminRoutes as $route => $expectedStatus) {
            $response = $this->actingAs($admin)->get($route);
            $response->assertStatus($expectedStatus);
        }
    }
}
