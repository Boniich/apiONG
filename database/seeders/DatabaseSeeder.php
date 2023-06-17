<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(MemberSeeder::class);
        $this->call(OrganizationSeeder::class);
        $this->call(ContactSeeder::class);
        $this->call(TestimonialSeeder::class);
        $this->call(SocialMediaItemSeeder::class);
        $this->call(ProjectSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(SlideSeeder::class);
        $this->call(ActivitySeeder::class);
        $this->call(NewsSeeder::class);
        $this->call(CommentSeeder::class);
    }
}
