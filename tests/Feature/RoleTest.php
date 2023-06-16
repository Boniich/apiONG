<?php

namespace Tests\Feature;

use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleTest extends TestCase
{

    use RefreshDatabase;

    private string $path = "api/roles/";

    public function test_show_all_roles_succesfully(): void
    {
        $this->seed(RoleSeeder::class);
        $response = $this->get($this->path);

        $response->assertStatus(200);
    }

    public function test_show_one_role_by_id_succesfully(): void
    {
        $this->seed(RoleSeeder::class);
        $response = $this->get($this->path . 1);

        $response->assertStatus(200);
    }

    public function test_not_found_role_data_to_show_details(): void
    {
        $this->seed(RoleSeeder::class);
        $response = $this->get($this->path . 5);

        $response->assertStatus(404);
    }

    public function test_update_role_data_successfully(): void
    {
        $this->seed(RoleSeeder::class);
        $this->put($this->path . 1, [
            'name' => 'testing role',
        ])->assertStatus(200);
    }

    public function test_not_found_role_data_to_update(): void
    {
        $this->seed(RoleSeeder::class);
        $this->put($this->path . 10, [
            "name" => "testing role",
        ])->assertStatus(404);
    }

    public function test_bad_request_to_update_role_data(): void
    {
        $this->seed(RoleSeeder::class);
        $this->put($this->path . 1, [
            "title" => "testing role",
        ])->assertStatus(400);
    }
}
