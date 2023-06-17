<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category1 = new Category();

        $category1->id = 1;
        $category1->name = "Category 1";
        $category1->description = "description of category 1";

        $category1->save();


        $category2 = new Category();

        $category2->id = 2;
        $category2->name = "Category 2";
        $category2->description = "description of category 2";

        $category2->save();
    }
}
