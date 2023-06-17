<?php

namespace Tests\Feature;

use App\Models\Slide;
use Database\Seeders\SlideSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SlideTest extends TestCase
{
    use RefreshDatabase;

    private string $path = 'api/slides/';

    public function test_show_all_slide_successfully(): void
    {
        $this->seed(SlideSeeder::class);
        $response = $this->get($this->path);

        $response->assertStatus(200);
    }

    public function test_show_one_slide_by_id_successfully(): void
    {
        $this->seed(SlideSeeder::class);
        $response = $this->get($this->path . 1);

        $response->assertStatus(200);
    }

    public function test_not_found_slide_to_show_details_successfully()
    {
        $response = $this->get($this->path . 1);

        $response->assertStatus(404);
    }

    public function test_create_slide_successfully()
    {
        $this->post($this->path, [
            'id' => 2,
            'name' => 'slider test',
            'description' => 'testing create a slider',
            'image' => UploadedFile::fake()->image("img.png"),
            'order' => 1,
        ])->assertStatus(200);

        $imageCreated = Slide::find(2);
        deleteLoadedImage($imageCreated->image);
    }

    public function test_bad_request_at_create_slide()
    {
        $this->post($this->path, [
            'id' => 2,
            'name' => 'slider test',
            'description' => 'testing create a slider',
        ])->assertStatus(400);
    }

    public function test_update_slide_data_successfully()
    {
        $this->seed(SlideSeeder::class);
        $this->put($this->path . 1, [
            'name' => 'slider test 2',
            'description' => 'testing create a slider 2',
        ])->assertStatus(200);

        $imageCreated = Slide::find(1);
        deleteLoadedImage($imageCreated->image);
    }

    public function test_not_found_slide_data_to_update_successfully()
    {
        $this->seed(SlideSeeder::class);
        $this->put($this->path . 100, [
            'name' => 'slider test 2',
            'description' => 'testing create a slider 2',
        ])->assertStatus(404);
    }

    public function test_delete_slide_data_successfully()
    {
        $this->seed(SlideSeeder::class);
        $this->delete($this->path . 1)->assertStatus(200);
    }

    public function test_not_found_slide_data_to_delete()
    {
        $this->seed(SlideSeeder::class);
        $this->delete($this->path . 100)->assertStatus(404);
    }
}
