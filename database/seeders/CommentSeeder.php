<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comment = new Comment;

        $comment->id = 1;
        $comment->text = "Comment 1";
        $comment->visible = true;

        $comment->save();
    }
}
