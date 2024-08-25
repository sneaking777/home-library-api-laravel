<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика книг
 *
 * @extends Factory<Book>
 * @package Database\Factories
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 25.08.2024 19:47
 */
class BookFactory extends Factory
{
    /**
     * Определение состояния модели по умолчанию.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
            'author_id' => Author::factory()->create(),
            'deleted_at' => null,
        ];
    }
}
