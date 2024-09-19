<?php

namespace Tests\Feature\auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_user_login_with_password(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(302);

        $response->assertRedirect(route('home')); 

        $this->assertAuthenticatedAs($user);
    }

    public function test_if_user_email_not_valid_return_error()
    {
        $response = $this->post(route('login'), [
            'email' => 'yaroslav2023@gmail.com',
            'password' => 'password',
        ]);
    
        $response->assertStatus(302);
    }

    public function test_show_error_if_user_password_not_correct()
    {
        $user = User::Factory()->create();

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);
    
        $response->assertStatus(302);

        $response->assertRedirect(route('home')); 

        $this->assertAuthenticatedAs($user);
    }
}
