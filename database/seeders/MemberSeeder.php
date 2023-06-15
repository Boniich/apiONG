<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;

class MemberSeeder extends Seeder
{
    public function run(): void
    {

        $member = new Member();

        $member->id = 1;
        $member->full_name = "Mario Hernandez";
        $member->description = "Director de la ONG";
        $member->image = upLoadImageToSeeders("member");
        $member->facebook_url = "facebook de mario";
        $member->linkedin_url = "linkedin de mario";

        $member->save();
    }
}
