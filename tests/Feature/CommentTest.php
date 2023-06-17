<?php

namespace Tests\Feature;

use App\Models\Comment;
use Database\Seeders\CommentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    private string $path = 'api/comments/';

    public function test_show_all_comments_successfully(): void
    {
        $this->seed(CommentSeeder::class);
        $response = $this->get($this->path);

        $response->assertStatus(200);
    }

    public function test_show_one_comment_by_id_successfully(): void
    {
        $this->seed(CommentSeeder::class);
        $response = $this->get($this->path . 1);

        $response->assertStatus(200);
    }

    public function test_not_found_comment_to_show_details_successfully()
    {
        $response = $this->get($this->path . 1);

        $response->assertStatus(404);
    }

    public function test_create_comment_successfully()
    {
        $this->post($this->path, [
            'id' => 2,
            'text' => 'new comment',
            'image' => UploadedFile::fake()->image("img.png"),
            'visible' => true

        ])->assertStatus(200);

        $imageCreated = Comment::find(2);
        deleteLoadedImage($imageCreated->image);
    }

    public function test_bad_request_at_create_comment()
    {
        $this->post($this->path, [
            'id' => 2,
            'image' => UploadedFile::fake()->image("img.png"),
            'visible' => true
        ])->assertStatus(400);
    }

    public function test_update_comment_data_successfully()
    {
        $this->seed(CommentSeeder::class);
        $this->put($this->path . 1, [
            'text' => 'new comment 2',
        ])->assertStatus(200);
    }

    public function test_not_found_comment_data_to_update_successfully()
    {
        $this->seed(CommentSeeder::class);
        $this->put($this->path . 100, [
            'text' => 'new comment',
            'image' => UploadedFile::fake()->image("img.png"),
            'visible' => true
        ])->assertStatus(404);
    }

    public function test_delete_comment_data_successfully()
    {
        $this->seed(CommentSeeder::class);
        $this->delete($this->path . 1)->assertStatus(200);
    }

    public function test_not_found_comment_data_to_delete()
    {
        $this->seed(CommentSeeder::class);
        $this->delete($this->path . 100)->assertStatus(404);
    }
}
