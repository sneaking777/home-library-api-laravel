<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика авторов
 *
 * @package Database\Factories
 * @extends Factory<Author>
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 11.08.2024 18:45
 */
class AuthorFactory extends Factory
{

    /**
     * Связанная с фабрикой модель.
     *
     * @var string
     */
    protected $model = Author::class;

    /**
     * Определение состояния модели по умолчанию.
     *
     * @return array
     */
    public function definition(): array
    {
        $gender = rand(1, 2) === 1 ? 'male' : 'female';
        $firstName = $this->faker->firstName($gender);
        $lastName = $this->faker->lastName($gender);
        $patronymic = rand(1, 20) === 1 ? null : $this->makePatronymic($gender);

        return [
            'name' => $firstName,
            'surname' => $lastName,
            'patronymic' => $patronymic
        ];
    }

    /**
     * Склонение имени в русском языке для отчества.
     *
     * @param $gender
     * @return string
     */
    private function makePatronymic($gender): string
    {
        $firstName = $this->faker->firstName('male');
        $end = mb_substr($firstName, -1);
        if ($end === 'й') {
            $baseName = mb_substr($firstName, 0, -1);
            $suffix = $gender === 'male' ? 'евич' : 'ьевна';
        } else if ($end === 'ь') {
            $suffix = $gender === 'male' ? 'ич' : 'на';
            $baseName = $firstName;
        } else if ($end === 'я') {
            $suffix = $gender === 'male' ? 'ич' : 'ьевна';
            $baseName = $firstName;

        } else if ($end === 'а') {
            $suffix = $gender === 'male' ? 'ич' : 'на';
            $baseName = $firstName;
        }
        else {
                $suffix = $gender === 'male' ? 'ович' : 'овна';
                $baseName = $firstName;
            }

            return $baseName . $suffix;

        }

    }
