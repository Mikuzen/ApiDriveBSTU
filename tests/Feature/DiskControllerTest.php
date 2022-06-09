<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
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
                        'link',
                    ]
                ]
            ]);
    }

    public function test_api_upload_image()
    {
        $user_id = User::first()->id;
        $file = UploadedFile::fake()->image('image.png', 500, 500)->size(100);
        $response = $this->postJson('/api/V1/files', [
            'user_id' => $user_id,
            'files' => [$file],
        ])
            ->assertOk()
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
                        'link',
                    ]
                ]
            ]);

        $data = json_decode($response->getContent())->data[0];
        $this->assertDatabaseHas('files', [
            'id' => $data->id,
            'user_id' => $user_id,
            'src' => $data->src,
            'ext' => $data->ext,
            'title' => $data->title,
            'size' => $data->size,
            'type' => $data->type,
            'private' => $data->private,
            'created_at' => $data->created_at,
        ]);

        $this->assertFileExists(public_path('\\storage\\files\\' . $user_id . '\\' .
            Carbon::now()->toDateString() . '\\' . $data->src));
    }

    public function test_api_upload_file()
    {
        $user_id = User::first()->id;
        $file = UploadedFile::fake()->create('audio.mp3', 500, 'audio/mp3');
        $response = $this->postJson('/api/V1/files', [
            'user_id' => $user_id,
            'files' => [$file],
        ])
            ->assertOk()
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
                        'link',
                    ]
                ]
            ]);

        $data = json_decode($response->getContent())->data[0];
        $this->assertDatabaseHas('files', [
            'id' => $data->id,
            'user_id' => $user_id,
            'src' => $data->src,
            'ext' => $data->ext,
            'title' => $data->title,
            'size' => $data->size,
            'type' => $data->type,
            'private' => $data->private,
            'created_at' => $data->created_at,
        ]);

        $this->assertFileExists(public_path('\\storage\\files\\' . $user_id . '\\' .
            Carbon::now()->toDateString() . '\\' . $data->src));
    }

    public function test_api_upload_several_files()
    {
        $user_id = User::first()->id;
        $file = [
            UploadedFile::fake()->create('audio.mp3', 500, 'audio/mp3'),
            UploadedFile::fake()->image('image.png', 500, 500)->size(100)
        ];
        $response = $this->postJson('/api/V1/files', [
            'user_id' => $user_id,
            'files' => [$file[0],
                $file[1]],
        ])
            ->assertOk()
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
                        'link',
                    ]
                ]
            ]);

        $dataFiles = json_decode($response->getContent())->data;

        foreach ($dataFiles as $dataFile) {
            $this->assertDatabaseHas('files', [
                'id' => $dataFile->id,
                'user_id' => $user_id,
                'src' => $dataFile->src,
                'ext' => $dataFile->ext,
                'title' => $dataFile->title,
                'size' => $dataFile->size,
                'type' => $dataFile->type,
                'private' => $dataFile->private,
                'created_at' => $dataFile->created_at,
            ]);

            $this->assertFileExists(public_path('\\storage\\files\\' . $user_id . '\\' .
                Carbon::now()->toDateString() . '\\' . $dataFile->src));
        }
    }

    public function test_api_show_file()
    {
        $user_id = User::first()->id;
        $file = UploadedFile::fake()->create('audio.mp3', 500, 'audio/mp3');
        $response = $this->postJson('/api/V1/files', [
            'user_id' => $user_id,
            'files' => [$file],
        ]);

        $file = json_decode($response->getContent())->data[0];

        $this->getJson('/api/V1/files/' . $file->id)
            ->assertOk()
            ->assertExactJson([
                'data' => [
                    'id' => $file->id,
                    'user_id' => $user_id,
                    'src' => $file->src,
                    'ext' => $file->ext,
                    'title' => $file->title,
                    'size' => $file->size,
                    'type' => $file->type,
                    'private' => (integer)$file->private,
                    'created_at' => $file->created_at,
                    'deleted_at' => $file->deleted_at,
                    'link' => $file->link,
                ]
            ]);
    }

    public function test_api_destroy_file()
    {
        $user_id = User::first()->id;
        $file = UploadedFile::fake()->create('audio.mp3', 500, 'audio/mp3');
        $response = $this->postJson('/api/V1/files', [
            'user_id' => $user_id,
            'files' => [$file],
        ]);

        $file = json_decode($response->getContent())->data[0];
        $this->deleteJson('/api/V1/files/' . $file->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('files', [
            'id' => $file->id,
            'src' => $file->src,
        ]);
    }

    public function test_api_store_missing_file()
    {
        $this->postJson('/api/V1/files', [
            'user_id' => User::first()->id,
            'files' => [],
        ])
            ->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }

    public function test_api_store_not_valid_mime_type_file()
    {
        $file = UploadedFile::fake()->create('document.bmp', 1000, 'application/ogg');
        $this->postJson('/api/V1/files', [
            'user_id' => User::first()->id,
            'files' => [$file],
        ])
            ->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }

    public function test_api_store_file_with_size_more_than_max()
    {
        $file = UploadedFile::fake()->create('document.pdf', 2049, 'application/pdf');
        $this->postJson('/api/V1/files', [
            'user_id' => User::first()->id,
            'files' => [$file],
        ])
            ->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }

    public function test_api_show_missing_file()
    {
        $this->getJson('/api/V1/files/0')
            ->assertNotFound()
            ->assertJsonStructure(['exception']);
    }

    public function test_api_destroy_missing_file()
    {
        $this->deleteJson('/api/V1/files/0')
            ->assertNotFound()
            ->assertJsonStructure(['exception']);
    }
}
