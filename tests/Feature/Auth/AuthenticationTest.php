<?php

namespace Tests\Feature\Auth;

use App\Models\Applicant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $applicant = Applicant::create([
            'name' => 'Test Applicant',
            'email' => 'testapplicant@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => $applicant->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated('web');
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $applicant = Applicant::create([
            'name' => 'Test Applicant',
            'email' => 'testapplicant@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->post('/login', [
            'email' => $applicant->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest('web');
    }

    public function test_users_can_logout(): void
    {
        $applicant = Applicant::create([
            'name' => 'Test Applicant',
            'email' => 'testapplicant@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($applicant, 'web')->post('/logout');

        $this->assertGuest('web');
        $response->assertRedirect('/');
    }
}
