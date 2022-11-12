<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\AllowedDomains;
use App\Models\Form;
use App\Models\User;
use App\Models\Question;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();

        Form::factory(5)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Question::create([
            "form_id" => 1,
            "name" => "Manakan bahasa pemograman yang paling populer?",
            "choice_type" => "multiple choices",
            "is_required" => 1,
            "choices" => "Python,JavaScript,VueJS,HTML"
        ]);
        Question::create([
            "form_id" => 1,
            "name" => "Apakah melinda jelek?",
            "is_required" => 0,
            "choice_type" => "checkboxes",
            "choices" => "true,false"
        ]);

        AllowedDomains::create([
            "form_id" => 1,
            "domain" => "webtech.id"
        ]);

        AllowedDomains::create([
            "form_id" => 1,
            "domain" => "gmail.com"
        ]);

        User::create([
            "name" => "edwin",
            "email" => "edwin@gmail.com",
            "password" => bcrypt('edwin')
        ]);

        User::create([
            "name" => "someone",
            "email" => "someone@someone.com",
            "password" => bcrypt('someone')
        ]);
    }
}
