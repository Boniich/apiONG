<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activity = new Activity();

        $activity->id = 1;
        $activity->name = "Activity 1";
        $activity->slug = "slug of activity1";
        $activity->description = "Description of activity 1";
        $activity->image = upLoadImageToSeeders("activity");

        $activity->save();
    }
}
