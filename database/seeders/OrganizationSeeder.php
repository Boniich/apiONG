<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $organization = new Organization;

        $organization->id = 1;
        $organization->name = "Org name";
        $organization->logo = upLoadImageToSeeders("organization");
        $organization->short_description = "short description";
        $organization->long_description = "long description";
        $organization->welcome_text = "welcome text";
        $organization->address = "address";
        $organization->phone = "phone";
        $organization->cell_phone = "cell phone";
        $organization->facebook_url = "face url";
        $organization->linkedin_url = "link url";
        $organization->instagram_url = "inst url";
        $organization->twitter_url = "twitter url";

        $organization->save();
    }
}
