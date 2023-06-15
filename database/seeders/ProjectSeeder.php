<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $project = new Project;

        $project->id = 1;
        $project->name = "Project 1";
        $project->description = "Description of project 1";
        $project->image = upLoadImageToSeeders("projects");
        $project->due_date = "2023";

        $project->save();
    }
}
