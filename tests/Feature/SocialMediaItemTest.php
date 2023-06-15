<?php

namespace Tests\Feature;

use App\Models\SocialMediaItem;
use Database\Seeders\SocialMediaItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SocialMediaItemTest extends TestCase
{

    use RefreshDatabase;

    private string $path = 'api/socialMediaItem/';

    public function test_show_all_social_media_items_successfully(): void
    {
        $this->seed(SocialMediaItemSeeder::class);
        $response = $this->get($this->path);

        $response->assertStatus(200);
    }


    public function test_show_one_social_media_item_by_id_successfully(): void
    {
        $this->seed(SocialMediaItemSeeder::class);
        $response = $this->get($this->path . 1);

        $response->assertStatus(200);
    }

    public function test_not_found_social_media_item_to_show_details_successfully()
    {
        $response = $this->get($this->path . 1);

        $response->assertStatus(404);
    }

    public function test_create_social_media_item_successfully()
    {
        $this->post($this->path, [
            'id' => 2,
            'name' => 'Social media item testt',
            'image' => UploadedFile::fake()->image("img.png"),
            'url' => 'creating social media item',
        ])->assertStatus(200);

        $imageCreated = SocialMediaItem::find(2);
        deleteLoadedImage($imageCreated->image);
    }

    public function test_bad_request_at_create_social_media_item()
    {
        $this->post($this->path, [
            'id' => 2,
            'name' => 'Social media item test',
            'url' => 'creating social media item',
        ])->assertStatus(400);
    }

    public function test_update_data_social_media_item_successfully()
    {
        $this->seed(SocialMediaItemSeeder::class);
        $this->put($this->path . 1, [
            'name' => 'Social media item test',
            'image' => UploadedFile::fake()->image("img.png"),
            'url' => 'creating social media item',
        ])->assertStatus(200);

        $imageCreated = SocialMediaItem::find(1);
        deleteLoadedImage($imageCreated->image);
    }

    public function test_not_found_data_social_media_item_to_update_successfully()
    {
        $this->seed(SocialMediaItemSeeder::class);
        $this->put($this->path . 100, [
            'name' => 'Social media item test',
            'image' => UploadedFile::fake()->image("img.png"),
            'url' => 'creating social media item',
        ])->assertStatus(404);
    }

    public function test_bad_request_at_update_social_media_item()
    {
        $this->seed(SocialMediaItemSeeder::class);
        $this->put($this->path . 1, [
            'id' => 2,
            'name' => 'Social media item test',
            'url' => 'creating social media item',
        ])->assertStatus(400);
    }

    public function test_delete_data_social_media_item_successfully()
    {
        $this->seed(SocialMediaItemSeeder::class);
        $this->delete($this->path . 1)->assertStatus(200);
    }

    public function test_not_found_data_social_media_item_to_delete()
    {
        $this->seed(SocialMediaItemSeeder::class);
        $this->delete($this->path . 100)->assertStatus(404);
    }
}
