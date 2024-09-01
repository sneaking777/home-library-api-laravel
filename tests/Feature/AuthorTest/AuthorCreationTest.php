<?php

namespace AuthorTest;

use App\Enums\GendersEnum;
use App\FakerProviders\PatronymicFakerProvider;
use Tests\BaseFeatureTest;

/**
 * Класс AuthorCreationTest
 *
 * Этот класс содержит набор тестовых сценариев относящихся к процессу создания авторов в приложении.
 * Он включает в себя тесты, которые проверяют различные аспекты и возможные граничные случаи создания авторов
 *
 * @extends BaseFeatureTest
 * @package AuthorTest
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 01.09.2024 13:58
 */
class AuthorCreationTest extends BaseFeatureTest
{
    /**
     * @inheritdoc
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->setResponseJsonStructure([
            'message',
            'author' => [
                'id',
                'surname',
                'name',
                'patronymic',
            ]
        ]);
        $this->route = route('author.store');
        $gender = rand(1, 2) === 1 ? GendersEnum::MALE->value : GendersEnum::FEMALE->value;
        $this->faker->addProvider(new PatronymicFakerProvider($this->faker));
        $firstName = $this->faker->firstName($gender);
        $lastName = $this->faker->lastName($gender);
        $fatherName = $this->faker->firstName(GendersEnum::MALE->value);
        $patronymic = rand(1, 20) === 1 ? null : $this->faker->makeRussianPatronymic($gender, $fatherName);
        $this->data = [
            'surname' => $lastName,
            'name' => $firstName,
            'patronymic' => $patronymic,
        ];

    }

    /**
     * Сценарий создания автора
     *
     * @return void
     */
    public function test_author_creation() {

        $this->loginAsUser();
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsCreated($response)
            ->assertJsonIsObject()
            ->assertJsonStructure($this->getResponseJsonStructure())
            ->assertJsonPath('message', __('messages.success.author.created'))
            ->assertJsonPath('author.surname', $this->data['surname'])
            ->assertJsonPath('author.name', $this->data['name'])
            ->assertJsonPath('author.patronymic', $this->data['patronymic']);
        $responseArray = json_decode($response->getContent(), true);
        $this->assertIsString($responseArray['message']);
        $this->assertIsArray($responseArray['author']);
        $this->assertIsNumeric($responseArray['author']['id']);
        $this->assertIsString($responseArray['author']['surname']);
        $this->assertIsString($responseArray['author']['name']);
        $this->assertThat(
            $responseArray['author']['patronymic'],
            $this->logicalOr(
                $this->isType('string'),
                $this->isNull()
            )
        );
    }

    /**
     * Сценарий создания автора без авторизации
     *
     * @return void
     */
    public function test_author_creation_without_auth(): void
    {
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnauthorized($response);
        $response->assertJson(['message' => __('messages.unauthenticated')]);
    }

    /**
     * Сценарий создания автора с пустой фамилией
     *
     * @return void
     */
    public function test_author_creation_with_empty_surname()
    {
        $this->loginAsUser();
        $this->data['surname'] = '';

        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['surname']);
    }

    /**
     * Сценарий создания автора с пустым именем
     *
     * @return void
     */
    public function test_author_creation_with_empty_name()
    {
        $this->loginAsUser();
        $this->data['name'] = '';

        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * Сценарий создания автора с именем превышающем 100 символов
     *
     * @return void
     */
    public function test_author_creation_with_name_exceeding_max_length()
    {
        $this->loginAsUser();
        $this->data['name'] = str_repeat('a', 101);
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * Сценарий создания автора с фамилией превышающей 100 символов
     *
     * @return void
     */
    public function test_author_creation_with_surname_exceeding_max_length()
    {
        $this->loginAsUser();
        $this->data['surname'] = str_repeat('a', 101);
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['surname']);
    }

    /**
     * Сценарий создания автора с отчеством превышающим 100 символов
     *
     * @return void
     */
    public function test_author_creation_with_patronymic_exceeding_max_length()
    {
        $this->loginAsUser();
        $this->data['patronymic'] = str_repeat('a', 101);
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['patronymic']);
    }

    /**
     * Сценарий создания автора с невалидным типом имени
     *
     * @return void
     */
    public function test_author_creation_with_invalid_name_type()
    {
        $this->loginAsUser();
        $this->data['name'] = 111;
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * Сценарий создания автора с невалидным типом фамилии
     *
     * @return void
     */
    public function test_author_creation_with_invalid_surname_type()
    {
        $this->loginAsUser();
        $this->data['surname'] = 111;
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['surname']);
    }

    /**
     * Сценарий создания автора с невалидным типом отчества
     *
     * @return void
     */
    public function test_author_creation_with_invalid_patronymic_type()
    {
        $this->loginAsUser();
        $this->data['patronymic'] = 111;
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['patronymic']);
    }
}
