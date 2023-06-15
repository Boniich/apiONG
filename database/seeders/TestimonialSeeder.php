<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonial = new Testimonial;

        $testimonial->id = 1;
        $testimonial->name = "Testimonio 1";
        $testimonial->image = upLoadImageToSeeders("testimonial");
        $testimonial->description = "Descripcion del testimonio 1";

        $testimonial->save();
    }
}
