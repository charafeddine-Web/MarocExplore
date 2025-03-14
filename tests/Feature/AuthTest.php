<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Authentication.
     */
    public function test_user_id_login_correct(): void
    {
        $user = User::factory()->create([
            'email' => 'chraf@gmail.com',
            'password' => Hash::make('charaf2025'),
        ]);
        $response = $this->postJson('api/login', [
            'email' => $user->email,
            'password' => 'charaf2025'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'email',
                ],
                'token'
            ])
            ->assertJson([
                'user' => [
                    'email' => 'chraf@gmail.com',
                ]
            ]);
    }

    public function test_user_id_register_correct(): void
    {
        $response = $this->postJson('api/register', [
            "name" => 'ahmed',
            'email' => 'ahmed@gmail.com',
            'password' => '123456789',
            'password_confirmation' => '123456789'
        ]);

        $response->assertStatus(201);
    }
}
