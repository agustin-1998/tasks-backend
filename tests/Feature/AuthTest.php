<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        # Peticion de inicio de sesion
        $response = $this->postJson(route("login"), [
            "email"=> "mitchell.dakota@example.org",
            "password"=> "12345678",
        ]);

        $response->assertStatus(200);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        // Intenta iniciar sesión con credenciales incorrectas
        $response = $this->postJson('login', [
            'email' => 'invalid@example.com',
            'password' => 'wrongpassword'
        ]);

        // Verifica que la respuesta sea un error
        $response->assertStatus(404);
    }
}
