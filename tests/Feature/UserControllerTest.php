<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function test_api_get_all_users()
    {
        $this->getJson('api/V1/users')
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
        $password = Str::random(10);

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

    public function test_api_show_user()
    {
        $password = Hash::make(Str::random(10));

        $user = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'admin' => false,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $this->getJson('/api/V1/users/' . $user->id)
            ->assertOk()
            ->assertExactJson([
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'admin' => (integer)$user->admin,
                    'password' => $user->password,
                    'created_at' => $user->created_at,
                    'files' => $user->files,
                ]
            ]);
    }

    public function test_api_update_user()
    {
        $password = Hash::make(Str::random(10));

        $user = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'admin' => false,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $updateData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'admin' => true,
        ];

        $this->putJson('/api/V1/users/' . $user->id, [
            'name' => $updateData['name'],
            'email' => $updateData['email'],
            'admin' => $updateData['admin'],
        ])
            ->assertOk()
            ->assertExactJson([
                'data' => [
                    'id' => $user->id,
                    'name' => $updateData['name'],
                    'email' => $updateData['email'],
                    'admin' => $updateData['admin'],
                    'password' => $user->password,
                    'created_at' => $user->created_at,
                    'files' => $user->files,
                ]
            ]);
    }

    public function test_api_destroy_user()
    {
        $password = Hash::make(Str::random(10));

        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password,
        ];
        $user = User::create($userData);

        unset($userData['password_confirmation']);

        $this->deleteJson('/api/V1/users/' . $user->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('users', $userData);
    }

    public function test_api_show_missing_user()
    {
        $r = $this->getJson('api/V1/users/0')
            ->assertNotFound()
            ->assertJsonStructure(['exception']);
    }

    public function test_api_update_missing_user()
    {
        $updateData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'admin' => true,
        ];

        $r = $this->putJson('api/V1/users/0', [
            'name' => $updateData['name'],
            'email' => $updateData['email'],
            'admin' => $updateData['admin'],
        ])
            ->assertNotFound()
            ->assertJsonStructure(['exception']);
    }

    public function test_api_destroy_missing_user()
    {
        $r = $this->deleteJson('api/V1/users/0')
            ->assertNotFound()
            ->assertJsonStructure(['exception']);
    }

    public function test_api_store_missing_data()
    {
        $user = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'admin' => false,
            //missing password and password_confirmation
        ];

        $r = $this->postJson('/api/V1/users', [
            'name' => $user['name'],
            'email' => $user['email'],
            'admin' => $user['admin'],
        ])
            ->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }

    public function test_api_update_missing_data()
    {
        $password = Hash::make(Str::random(10));

        $user = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'admin' => false,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $updateData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'admin' => 3,
        ];

        $this->putJson('/api/V1/users/' . $user->id, [
            'name' => $updateData['name'],
            'email' => $updateData['email'],
            'admin' => $updateData['admin'],
        ])
            ->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }
}
