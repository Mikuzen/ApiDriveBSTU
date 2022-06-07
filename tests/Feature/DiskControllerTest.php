<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DiskControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_get_all_files()
    {
        $this->getJson('api/V1/files')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'src',
                        'ext',
                        'title',
                        'size',
                        'type',
                        'private',
                        'created_at',
                        'deleted_at',
                        'links',
                    ]
                ]
            ]);
    }

//    public function test_api_store_one_file() {
//
//        $user_id = 5;
//
//        $this->postJson('/api/V1/files', [
//            'user_id' => $user_id,
//            'files' => [
//                UploadedFile::fake()->image('photo1.jpg'),
//                UploadedFile::fake()->image('photo2.jpg'),
//            ],
//        ]);
//
//        Storage::disk('public')->assertExists('/files/'.$user_id.'/photo1.jpg');
//    }

    public function test_api_store_several_file() {

    }
}
