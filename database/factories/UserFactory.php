<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Фабрика пользователей
 *
 * @extends Factory<User>
 * @package Database\Factories
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 25.08.2024 19:42
 */
class UserFactory extends Factory
{
    /**
     * @var string|null
     */
    protected static ?string $password;

    /**
     * Определение состояния модели по умолчанию.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
