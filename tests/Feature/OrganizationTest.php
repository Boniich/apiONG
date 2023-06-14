<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CreatesApplication;
use Tests\TestCase;

class OrganizationTest extends TestCase
{

    use RefreshDatabase;

    public function test_show_unique_register_of_organization_successfully(): void
    {
        $this->seed();
        $response = $this->get('api/organization');
        $response->assertStatus(200);
    }

    public function test_update__unique_register_of_organization_successfully(): void
    {
        $this->seed();
        $response = $this->put('api/organization', [
            'name' => 'Actualizado',
            'logo' => 'Actualizado',
            'short_description' => 'Actualizado',
            'long_description' => 'Actualizado',
            'welcome_text' => 'Actualizado',
            'address' => 'Actualizado',
            'phone' => 'Actualizado',
            'cell_phone' => 'Actualizado',
            'facebook_url' => 'Actualizado',
            'linkedin_url' => 'Actualizado',
            'instagram_url' => 'Actualizado',
            'twitter_url' => 'Actualizado',
        ]);

        $response->assertStatus(200);
    }

    public function test_bad_request_at_update_organization_data_successfully(): void
    {
        $this->seed();
        $response = $this->put('api/organization', [
            'name' => 'Actualizado',
            'logo' => 'Actualizado',
            'short_description' => 'Actualizado',
            'long_description' => 'Actualizado',
        ]);

        $response->assertStatus(400);
    }
}
