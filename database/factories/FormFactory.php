<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Form>
 */
class FormFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "name" => fake()->sentence(mt_rand(2, 4)),
            "slug" => fake()->slug(),
            "description" => fake()->paragraphs(mt_rand(2, 3), true),
            "limit_one_response" => mt_rand(0, 1),
            "creator_id" => mt_rand(1, 10)
        ];
    }
}
