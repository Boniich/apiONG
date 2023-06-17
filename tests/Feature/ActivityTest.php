<?php

namespace Tests\Feature;

use App\Models\Activity;
use Database\Seeders\ActivitySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    private string $path = 'api/activities/';

    public function test_show_all_activities_successfully(): void
    {
        $this->seed(ActivitySeeder::class);
        $response = $this->get($this->path);

        $response->assertStatus(200);
    }

    public function test_show_one_activity_by_id_successfully(): void
    {
        $this->seed(ActivitySeeder::class);
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
            'name' => 'activity test',
            'description' => 'testing create a activity',
            'image' => UploadedFile::fake()->image("img.png"),

        ])->assertStatus(200);

        $imageCreated = Activity::find(2);
        deleteLoadedImage($imageCreated->image);
    }

    public function test_bad_request_at_create_activity()
    {
        $this->post($this->path, [
            'id' => 2,
            'name' => 'activity test',
            'description' => 'testing create a activity',
        ])->assertStatus(400);
    }

    public function test_update_activity_data_successfully()
    {
        $this->seed(ActivitySeeder::class);
        $this->put($this->path . 1, [
            'name' => 'updating activity',
        ])->assertStatus(200);

        $imageCreated = Activity::find(1);
        deleteLoadedImage($imageCreated->image);
    }

    public function test_not_found_activity_data_to_update_successfully()
    {
        $this->seed(ActivitySeeder::class);
        $this->put($this->path . 100, [
            'name' => 'activity test',
            'description' => 'testing create a activity',
            'image' => UploadedFile::fake()->image("img.png"),
        ])->assertStatus(404);
    }

    public function test_delete_activity_data_successfully()
    {
        $this->seed(ActivitySeeder::class);
        $this->delete($this->path . 1)->assertStatus(200);
    }

    public function test_not_found_activity_data_to_delete()
    {
        $this->seed(ActivitySeeder::class);
        $this->delete($this->path . 100)->assertStatus(404);
    }
}
