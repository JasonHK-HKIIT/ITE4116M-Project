<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NewsArticleContent>
 */
class NewsArticleContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'thumbnail' => fake()->uuid() . '.jpg',
            'title' => fake()->sentence(10),
            'content' => fake()->paragraphs(10, true),
        ];
    }
}
