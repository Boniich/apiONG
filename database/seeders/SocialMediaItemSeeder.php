<?php

namespace Database\Seeders;

use App\Models\SocialMediaItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialMediaItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $socialMediaItem = new SocialMediaItem;

        $socialMediaItem->id = 1;
        $socialMediaItem->name = "Social media item 1";
        $socialMediaItem->image = upLoadImageToSeeders("SocialMediaItem");
        $socialMediaItem->url = "url-media-item-1";

        $socialMediaItem->save();
    }
}
