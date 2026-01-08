<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ResourceTranslation>
 */
class ResourceTranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(6)
        ];
    }

    public function resource(): static
    {
        return $this->afterCreating(function ($resource)
        {
            $resource->addMedia(Storage::disk('local')->path('factories/resource.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        });
    }
}
