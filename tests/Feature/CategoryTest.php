<?php

namespace Tests\Feature;

use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    private string $path = 'api/categories/';

    public function test_show_all_category_successfully(): void
    {
        $this->seed(CategorySeeder::class);
        $response = $this->get($this->path);

        $response->assertStatus(200);
    }

    public function test_show_one_category_by_id_successfully(): void
    {
        $this->seed(CategorySeeder::class);
        $response = $this->get($this->path . 1);

        $response->assertStatus(200);
    }

    public function test_not_found_category_to_show_details_successfully()
    {
        $response = $this->get($this->path . 1);

        $response->assertStatus(404);
    }

    public function test_create_category_with_image_successfully()
    {
        $this->post($this->path, [
            'id' => 3,
            'name' => 'Category test',
            'description' => 'category testing',
            'image' => UploadedFile::fake()->image("img.png"),
        ])->assertStatus(200);

        $imageCreated = Category::find(3);
        deleteLoadedImage($imageCreated->image);
    }

    public function test_create_category_without_image_successfully()
    {
        $this->post($this->path, [
            'id' => 2,
            'name' => 'Category test',
            'description' => 'category testing',
        ])->assertStatus(200);
    }

    public function test_bad_request_at_create_category()
    {
        $this->post($this->path, [
            'id' => 2,
            'description' => 'category testing',
        ])->assertStatus(400);
    }

    public function test_update_data_category_successfully()
    {
        $this->seed(CategorySeeder::class);
        $this->put($this->path . 1, [
            'name' => 'Category test 2',
            'description' => 'category testing 2',
        ])->assertStatus(200);
    }

    public function test_not_found_data_category_to_update_successfully()
    {
        $this->seed(CategorySeeder::class);
        $this->put($this->path . 100, [
            'name' => 'Category test',
            'description' => 'category testing',
        ])->assertStatus(404);
    }

    public function test_delete_data_category_successfully()
    {
        $this->seed(CategorySeeder::class);
        $this->delete($this->path . 1)->assertStatus(200);
    }

    public function test_not_found_data_category_to_delete()
    {
        $this->seed(CategorySeeder::class);
        $this->delete($this->path . 100)->assertStatus(404);
    }
}
