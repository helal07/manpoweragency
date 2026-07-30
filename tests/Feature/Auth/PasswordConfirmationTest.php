<?php

namespace Tests\Feature\Auth;

use App\Models\Applicant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirm_password_screen_can_be_rendered(): void
    {
        $applicant = Applicant::create([
            'name' => 'Test Applicant',
            'email' => 'applicant@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($applicant, 'web')->get('/confirm-password');

        $response->assertStatus(200);
    }

    public function test_password_can_be_confirmed(): void
    {
        $applicant = Applicant::create([
            'name' => 'Test Applicant',
            'email' => 'applicant@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($applicant, 'web')->post('/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_password_is_not_confirmed_with_invalid_password(): void
    {
        $applicant = Applicant::create([
            'name' => 'Test Applicant',
            'email' => 'applicant@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($applicant, 'web')->post('/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }
}
