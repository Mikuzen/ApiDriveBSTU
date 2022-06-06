<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_get_all_users()
    {
        $response = $this->getJson('api/V1/users');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'admin',
                        'password',
                        'created_at',
                        'files'
                    ]
                ]
            ]);
    }

    public function test_api_add_new_user()
    {
        $password = $this->faker->password();

        $user = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'admin' => false,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $this->postJson('api/V1/users', $user)
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'admin',
                    'password',
                    'created_at',
                    'files'
                ]
            ]);
        $this->assertDatabaseHas('users', [
            'name' => $user['name'],
            'email' => $user['email'],
            'admin' => $user['admin'],
        ]);
    }

    public function test_api_show_user() {
        $password = $this->faker->password;

        $user = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'admin' => false,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $this->getJson('api/V1/users', ['user' => $user->id])
            ->assertStatus(200)
            ->assertExactJson([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'admin' => $user->admin,
                'password' => Hash::check($user->password, User::findOrFail()),
                'created_at' => (string)$user->created_at,
            ]);
    }

//    public function test_api_update_user() {
//
//    }
//
//    public function test_api_delete_user() {
//
//    }
}
