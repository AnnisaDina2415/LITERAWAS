<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function test_super_admin_can_access_history_reports_and_settings(): void
    {
        $admin = \App\Models\User::create([
            'name' => 'Super Admin Test',
            'email' => 'admintest@literawas.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'super_admin'
        ]);

        $response = $this->actingAs($admin)->get('/admin/borrows/history');
        $response->assertStatus(200);

        $response2 = $this->actingAs($admin)->get('/reports');
        $response2->assertStatus(200);

        $response3 = $this->actingAs($admin)->get('/admin/settings');
        $response3->assertStatus(200);
    }
}
