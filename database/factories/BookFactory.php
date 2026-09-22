<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
final class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(rand(2, 5), false),
            'author_id' => Author::factory(),
            'published_date' => fake()->date('Y-m-d'),
        ];
    }
}
