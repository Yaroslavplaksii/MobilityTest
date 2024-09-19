<?php

namespace Tests\Feature\auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_user_register()
    {
        $this->postJson(route('register'), 
            ['name' => 'Yaroslav', 
            'email' => 'yaroslav@gmail.com', 
            'password' => 'password',
            'password_confirmation' => 'password'])
            ->assertCreated();

        $this->assertDatabaseHas('users', ['name' => 'Yaroslav']);
    }
}
