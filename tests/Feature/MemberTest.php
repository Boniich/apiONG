<?php

namespace Tests\Feature;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MemberTest extends TestCase
{

    use RefreshDatabase;

    public function test_show_all_members_successfully(): void
    {
        $this->seed();
        $response = $this->get('api/members');

        $response->assertStatus(200);
    }

    public function test_show_one_member_by_id_successfully(): void
    {
        $this->seed();
        $response = $this->get('api/members/' . 1);

        $response->assertStatus(200);
    }

    public function test_not_found_member_to_show_details_successfully()
    {
        $response = $this->get('api/members/' . 1);

        $response->assertStatus(404);
    }

    //no funciona
    // public function test_create_member_successfully()
    // {

    //     // $img = asset('storage/app/public/imag.png');

    //     $img = Storage::disk('public')->get('image.png');

    //     //dd($img);

    //     $this->post('api/members', [
    //         'full_name' => 'Juan Carlos de la Cruz',
    //         'description' => 'Manejador de los fondos de la ONG',
    //         'image' => storage_path() . 'image.png',
    //         'facebook_url' => 'face de Juan',
    //         'linkedin_url' => 'link de Juan',
    //     ])->assertStatus(201);
    // }
}
