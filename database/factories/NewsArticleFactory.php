<?php

namespace Database\Factories;

use App\Enums\NewsArticleStatus;
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
            'status' => NewsArticleStatus::Published,
            'published_on' => fake()->date(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) =>
            [
                'status' => NewsArticleStatus::Draft,
                'published_on' => null,
            ]);
    }
}
