<?php

namespace Tests\Feature;

use App\Models\Testimonial;
use Database\Seeders\TestimonialSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class TestimonialTest extends TestCase
{

    use RefreshDatabase;

    private string $path = 'api/testimonials/';

    public function test_show_all_testimonial_successfully(): void
    {
        $this->seed(TestimonialSeeder::class);
        $response = $this->get($this->path);

        $response->assertStatus(200);
    }

    public function test_show_one_testimonial_by_id_successfully(): void
    {
        $this->seed(TestimonialSeeder::class);
        $response = $this->get($this->path . 1);

        $response->assertStatus(200);
    }

    public function test_not_found_testimonial_to_show_details_successfully()
    {
        $response = $this->get($this->path . 1);

        $response->assertStatus(404);
    }

    public function test_create_testimonial_successfully()
    {
        $this->post($this->path, [
            'id' => 2,
            'name' => 'Testimonial test',
            'image' => UploadedFile::fake()->image("img.png"),
            'description' => 'testing create a testimonial',
        ])->assertStatus(200);

        $imageCreated = Testimonial::find(2);
        deleteLoadedImage($imageCreated->image);
    }

    public function test_bad_request_at_create_testimonial()
    {
        $this->post($this->path, [
            'id' => 2,
            'name' => 'testimonial test',
            'description' => 'testing create a testimonial',
        ])->assertStatus(400);
    }

    public function test_update_data_testimonial_successfully()
    {
        $this->seed(TestimonialSeeder::class);
        $this->put($this->path . 1, [
            'name' => 'Testimonial test',
            'image' => UploadedFile::fake()->image("img.png"),
            'description' => 'testing create a testimonial',
        ])->assertStatus(200);

        $imageCreated = Testimonial::find(1);
        deleteLoadedImage($imageCreated->image);
    }

    public function test_not_found_data_testimonial_to_update_successfully()
    {
        $this->seed(TestimonialSeeder::class);
        $this->put($this->path . 100, [
            'name' => 'Testimonial test',
            'image' => UploadedFile::fake()->image("img.png"),
            'description' => 'testing create a testimonial',
        ])->assertStatus(404);
    }

    public function test_bad_request_at_update_testimonial()
    {
        $this->seed(TestimonialSeeder::class);
        $this->put($this->path . 1, [
            'id' => 2,
            'name' => 'testimonial test',
            'description' => 'testing create a testimonial',
        ])->assertStatus(400);
    }

    public function test_delete_data_testimonial_successfully()
    {
        $this->seed(TestimonialSeeder::class);
        $this->delete($this->path . 1)->assertStatus(200);
    }

    public function test_not_found_data_testimonial_to_delete()
    {
        $this->seed(TestimonialSeeder::class);
        $this->delete($this->path . 100)->assertStatus(404);
    }
}
