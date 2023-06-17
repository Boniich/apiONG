<?php

namespace Database\Seeders;

use App\Models\Slide;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slider = new Slide();

        $slider->id = 1;
        $slider->name = "Slider 1";
        $slider->description = "First slider";
        $slider->image = upLoadImageToSeeders("slider");
        $slider->order = 1;

        $slider->save();
    }
}
