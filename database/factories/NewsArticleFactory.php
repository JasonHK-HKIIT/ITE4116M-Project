<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NewsArticle>
 */
class NewsArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(),
            'thumbnail' => fake()->uuid() . '.jpg',
            'title' => fake()->sentence(10),
            'content' => fake()->paragraphs(10, true),
            'is_published' => true,
            'published_on' => fake()->date(),
        ];
    }

    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) =>
            [
                'is_published' => false,
                'published_on' => null,
            ]);
    }
}
