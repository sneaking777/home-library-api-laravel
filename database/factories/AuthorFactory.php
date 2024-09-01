<?php

namespace Database\Factories;

use App\Enums\GendersEnum;
use App\FakerProviders\PatronymicFakerProvider;
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
     * @inheritdoc
     *
     * @return array
     */
    public function definition(): array
    {
        $gender = rand(1, 2) === 1 ? GendersEnum::MALE->value : GendersEnum::FEMALE->value;
        $firstName = $this->faker->firstName($gender);
        $lastName = $this->faker->lastName($gender);
        $this->faker->addProvider(new PatronymicFakerProvider($this->faker));
        $fatherName = $this->faker->firstName(GendersEnum::MALE->value);
        $patronymic = rand(1, 20) === 1 ? null : $this->faker->makeRussianPatronymic($gender, $fatherName);;

        return [
            'name' => $firstName,
            'surname' => $lastName,
            'patronymic' => $patronymic
        ];
    }


}
