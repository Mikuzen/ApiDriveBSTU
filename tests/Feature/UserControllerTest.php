<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        $response = $this->postJson('api/V1/users', [
            'name' => 'Artuom',
            'email' => 'artyom@email.ru',
            'admin' => true,
            'password' => '123123123',
            'password_confirmation' => '123123123',
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'created_at' => true
            ]);
    }
}
