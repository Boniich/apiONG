<?php

namespace Tests\Feature;

use Database\Seeders\ContactSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactTest extends TestCase
{

    use RefreshDatabase;

    private string $url = 'api/contacts/';

    public function test_show_all_contacts_successfully(): void
    {
        $this->seed(ContactSeeder::class);
        $response = $this->get($this->url);

        $response->assertStatus(200);
    }

    public function test_show_one_contact_by_id_successfully(): void
    {
        $this->seed(ContactSeeder::class);
        $response = $this->get($this->url . 1);

        $response->assertStatus(200);
    }

    public function test_not_found_contact_to_show_details_by_id__successfully(): void
    {
        $this->seed(ContactSeeder::class);
        $response = $this->get($this->url . 100);

        $response->assertStatus(404);
    }

    public function test_store_a_new_contact_successfully(): void
    {
        $this->post($this->url, [
            'name' => 'Marcos',
            'email' => 'marcos@gmail.com',
            'phone' => '11111',
            'message' => 'mensaje de marcos',
        ])->assertStatus(200);
    }

    public function test_update_contact_data_successfully(): void
    {
        $this->seed(ContactSeeder::class);
        $this->put($this->url . 1, [
            'name' => 'Marcos',
            'email' => 'marcos@gmail.com',
            'phone' => '11111',
            'message' => 'mensaje de marcos',
        ])->assertStatus(200);
    }

    public function test_not_found_contact_data_to_update_successfully(): void
    {
        $this->put($this->url . 1, [
            'name' => 'Marcos',
            'email' => 'marcos@gmail.com',
            'phone' => '11111',
            'message' => 'mensaje de marcos',
        ])->assertStatus(404);
    }

    public function test_bad_request_to_update_contact_data_successfully(): void
    {

        $this->seed(ContactSeeder::class);
        $this->put($this->url . 1, [
            'name' => 'Marcos',
            'email' => 'marcos@gmail.com',
        ])->assertStatus(400);
    }

    public function test_delete_one_contact_data_successfully(): void
    {
        $this->seed(ContactSeeder::class);
        $response = $this->delete($this->url . 1);

        $response->assertStatus(200);
    }

    public function test_not_found_one_contact_data_successfully(): void
    {
        $this->seed(ContactSeeder::class);
        $this->delete($this->url . 1);

        $response = $this->delete($this->url . 1);

        $response->assertStatus(404);
    }
}
