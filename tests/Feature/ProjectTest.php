<?php

namespace Tests\Feature;

use App\Models\Project;
use Database\Seeders\ProjectSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    private string $path = 'api/projects/';

    public function test_show_all_project_successfully(): void
    {
        $this->seed(ProjectSeeder::class);
        $response = $this->get($this->path);

        $response->assertStatus(200);
    }

    public function test_show_one_project_by_id_successfully(): void
    {
        $this->seed(ProjectSeeder::class);
        $response = $this->get($this->path . 1);

        $response->assertStatus(200);
    }

    public function test_not_found_project_to_show_details_successfully()
    {
        $response = $this->get($this->path . 1);

        $response->assertStatus(404);
    }

    public function test_create_project_successfully()
    {
        $this->post($this->path, [
            'id' => 2,
            'title' => 'Project test',
            'description' => 'description of project test',
            'image' => UploadedFile::fake()->image("img.png"),
            'due_date' => '2013',

        ])->assertStatus(200);

        $imageCreated = Project::find(2);
        deleteLoadedImage($imageCreated->image);
    }

    public function test_bad_request_at_create_project()
    {
        $this->post($this->path, [
            'id' => 2,
            'title' => 'Project test',
            'description' => 'description of project test',
        ])->assertStatus(400);
    }

    public function test_update_data_project_successfully()
    {
        $this->seed(ProjectSeeder::class);
        $this->put($this->path . 1, [
            'title' => 'Project test',
            'description' => 'description of project test',
            'image' => UploadedFile::fake()->image("img.png"),
            'due_date' => '2013',
        ])->assertStatus(200);

        $imageCreated = Project::find(1);
        deleteLoadedImage($imageCreated->image);
    }

    public function test_not_found_data_project_to_update_successfully()
    {
        $this->seed(ProjectSeeder::class);
        $this->put($this->path . 100, [
            'title' => 'Project test',
            'description' => 'description of project test',
            'image' => UploadedFile::fake()->image("img.png"),
            'due_date' => '2013',
        ])->assertStatus(404);
    }

    public function test_bad_request_at_update_project()
    {
        $this->seed(ProjectSeeder::class);
        $this->put($this->path . 1, [
            'id' => 2,
            'title' => 'Project test',
            'description' => 'description of project test',
        ])->assertStatus(400);
    }

    public function test_delete_data_project_successfully()
    {
        $this->seed(ProjectSeeder::class);
        $this->delete($this->path . 1)->assertStatus(200);
    }

    public function test_not_found_data_project_to_delete()
    {
        $this->seed(ProjectSeeder::class);
        $this->delete($this->path . 100)->assertStatus(404);
    }
}
