<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private string $path = 'api/users/';

    public function test_show_all_user_successfully(): void
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $response = $this->get($this->path);

        $response->assertStatus(200);
    }

    public function test_show_one_user_by_id_successfully(): void
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);

        $data = User::all('id');
        $response = $this->get($this->path . $data[0]->id);

        $response->assertStatus(200);
    }

    public function test_not_found_user_to_show_details_successfully()
    {
        $response = $this->get($this->path . 1);

        $response->assertStatus(404);
    }

    public function test_create_user_without_profile_image_successfully()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $this->post($this->path, [
            'name' => 'Carlos Perez',
            'email' => 'carlitos22@gmail.com',
            'password' => '12346',
            'role_id' => 2,
        ])->assertStatus(200);
    }

    public function test_create_user_with_profile_image_successfully()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $response = $this->post($this->path, [
            'name' => 'Carlos Perez',
            'email' => 'carlitos23@gmail.com',
            'password' => '12346',
            'profile_image' => UploadedFile::fake()->image("img.png"),
            'role_id' => 2,
        ])->assertStatus(200);

        $imageCreated = User::find(26);
        deleteLoadedImage($imageCreated->profile_image);
    }

    public function test_bad_request_at_create_user()
    {
        $this->post($this->path, [
            'id' => 2,
            'name' => 'Carlos Perez',
            'email' => 'carlitos@gmail.com',
        ])->assertStatus(400);
    }

    public function test_update_data_user_successfully()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $data = User::all('id');
        $this->put($this->path . $data[0]->id, [
            'name' => 'Carla Perez',
        ])->assertStatus(200);
    }

    public function test_not_found_data_user_to_update_successfully()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $this->put($this->path . 100, [
            'name' => 'Carlos Perez',
            'email' => 'carlitos@gmail.com',
            'password' => '12346',
            'role_id' => 2,
        ])->assertStatus(404);
    }

    public function test_bad_request_at_update_user()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $data = User::all(['id', 'email']);
        $this->put($this->path . $data[0]->id, ['email' => $data[0]->email])->assertStatus(400);
    }

    public function test_delete_data_user_successfully()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $data = User::all(['id', 'email']);
        $this->delete($this->path . $data[0]->id)->assertStatus(200);
    }

    public function test_not_found_data_user_to_delete()
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $this->delete($this->path . 100)->assertStatus(404);
    }
}
