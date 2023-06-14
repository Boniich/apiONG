<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contact = new Contact;

        $contact->id = 1;
        $contact->name = "Carlos Perez";
        $contact->email = "Carlosperez@gmail.com";
        $contact->phone = "111111110";
        $contact->message = "Mensajito de carlos";

        $contact->save();
    }
}
