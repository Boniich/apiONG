<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $news = new News();

        $news->id = 1;
        $news->name = "News 1";
        $news->slug = "Slug of new";
        $news->content = "Content of news 1";
        $news->image = upLoadImageToSeeders('news');

        $news->save();
    }
}
