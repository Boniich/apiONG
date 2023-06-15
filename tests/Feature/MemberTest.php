<?php

namespace Tests\Feature;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MemberTest extends TestCase
{

    use RefreshDatabase;

    private string $path = 'api/members/';

    public function test_show_all_members_successfully(): void
    {
        $this->seed();
        $response = $this->get($this->path);

        $response->assertStatus(200);
    }

    public function test_show_one_member_by_id_successfully(): void
    {
        $this->seed();
        $response = $this->get($this->path . 1);

        $response->assertStatus(200);
    }

    public function test_not_found_member_to_show_details_successfully()
    {
        $response = $this->get($this->path . 1);

        $response->assertStatus(404);
    }

    public function test_create_member_successfully()
    {
        $this->post($this->path, [
            'id' => 2,
            'full_name' => 'Juan Carlos de la Cruz',
            'description' => 'Manejador de los fondos de la ONG',
            'image' => UploadedFile::fake()->image("img.png"),
            'facebook_url' => 'face de Juan',
            'linkedin_url' => 'link de Juan',
        ])->assertStatus(200);

        $imageCreated = Member::find(2);
        deleteLoadedImage($imageCreated->image);
    }
}
