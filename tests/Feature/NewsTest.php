<?php

namespace Tests\Feature;

use App\Models\News;
use Database\Seeders\NewsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    private string $path = 'api/news/';

    public function test_show_all_activities_successfully(): void
    {
        $this->seed(NewsSeeder::class);
        $response = $this->get($this->path);

        $response->assertStatus(200);
    }

    public function test_show_one_activity_by_id_successfully(): void
    {
        $this->seed(NewsSeeder::class);
        $response = $this->get($this->path . 1);

        $response->assertStatus(200);
    }

    public function test_not_found_activity_to_show_details_successfully()
    {
        $response = $this->get($this->path . 1);

        $response->assertStatus(404);
    }

    public function test_create_activity_successfully()
    {
        $this->post($this->path, [
            'id' => 2,
            'name' => 'news for test',
            'content' => 'news content',
            'image' => UploadedFile::fake()->image("img.png"),

        ])->assertStatus(200);

        $imageCreated = News::find(2);
        deleteLoadedImage($imageCreated->image);
    }

    public function test_bad_request_at_create_activity()
    {
        $this->post($this->path, [
            'id' => 2,
            'name' => 'news for test',
            'description' => 'wrong field name',
        ])->assertStatus(400);
    }

    public function test_update_activity_data_successfully()
    {
        $this->seed(NewsSeeder::class);
        $this->put($this->path . 1, [
            'name' => 'updating news',
        ])->assertStatus(200);
    }

    public function test_not_found_activity_data_to_update_successfully()
    {
        $this->seed(NewsSeeder::class);
        $this->put($this->path . 100, [
            'name' => 'news for test',
            'content' => 'news content',
            'image' => UploadedFile::fake()->image("img.png"),
        ])->assertStatus(404);
    }

    public function test_delete_activity_data_successfully()
    {
        $this->seed(NewsSeeder::class);
        $this->delete($this->path . 1)->assertStatus(200);
    }

    public function test_not_found_activity_data_to_delete()
    {
        $this->seed(NewsSeeder::class);
        $this->delete($this->path . 100)->assertStatus(404);
    }
}
