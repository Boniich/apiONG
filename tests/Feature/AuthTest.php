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

    public function test_register_successfully(): void
    {
        $this->post('api/register', [
            'name' => 'Testint test',
            'email' => 'test45@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ])->assertStatus(200);
    }

    public function test_bad_request_error_at_register()
    {

        $this->post('api/register', [
            'name' => 'Testint test',
            'password' => '123456',
            'password_confirmation' => '123456'
        ])->assertStatus(400);
    }

    public function test_login_succesfully()
    {
        $user = new User();

        $user->name = "test";
        $user->email = "admin3@gmail.com";
        $user->password = Hash::make('123456');

        $user->save();


        $reponse = $this->post('api/login', [
            'email' => 'admin3@gmail.com',
            'password' => '123456',
        ]);

        $reponse->assertStatus(200);
    }

    public function test_validation_error_at_login()
    {

        $reponse = $this->post('api/login', [
            'email' => 'NotExistsUser@gmail.com',
            'password' => '123456',
        ]);

        $reponse->assertStatus(401);
    }
}
