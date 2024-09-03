<?php

namespace AuthorTest;

use App\Enums\GendersEnum;
use App\FakerProviders\PatronymicFakerProvider;
use App\Models\Author;
use Faker\Generator;
use Tests\BaseFeatureTest;

/**
 * Класс AuthorUpdateTest
 *
 * Этот класс содержит набор тестов для проверки функциональности обновления автора.
 *
 * @extends BaseFeatureTest
 * @package AuthorTest
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 02.09.2024 11:48
 */
class AuthorUpdateTest extends BaseFeatureTest
{
    /**
     * @inheritdoc
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->setResponseJsonStructure(
            [
                'message',
                'author' => [
                    'id',
                    'surname',
                    'name',
                    'patronymic',
                ]
            ]
        );
        $gender = rand(1, 2) === 1 ? GendersEnum::MALE->value : GendersEnum::FEMALE->value;
        $this->faker->addProvider(new PatronymicFakerProvider($this->faker));
        $firstName = $this->faker->firstName($gender);
        $lastName = $this->faker->lastName($gender);
        $fatherName = $this->faker->firstName(GendersEnum::MALE->value);
        /** @var Generator|PatronymicFakerProvider $fakerPatronymicProvider */
        $fakerPatronymicProvider = $this->faker;
        $patronymic = rand(1, 20) === 1 ? null : $fakerPatronymicProvider->makeRussianPatronymic($gender, $fatherName);
        $this->data = [
            'surname' => $lastName,
            'name' => $firstName,
            'patronymic' => $patronymic,
        ];
        $author = Author::factory()->create();
        $this->route = route('author.update', ['author' => $author->id]);
        $this->pathParameters['author'] = $author->id;
    }

    /**
     * Сценарий обновления автора
     *
     * @return void
     */
    public function test_author_update(): void
    {
        $this->loginAsUser();
        $response = parent::makePutJsonRequest();
        $response = parent::assertResponseStatusAsOk($response);
        $response->assertJsonStructure($this->getResponseJsonStructure());
        $responseData = $response->json();
        $this->assertIsString($responseData['message']);
        $this->assertIsArray($responseData['author']);
        $this->assertIsInt($responseData['author']['id']);
        $this->assertIsString($responseData['author']['surname']);
        $this->assertIsString($responseData['author']['name']);
        $patronymic = $responseData['author']['patronymic'];
        $this->assertIsString($patronymic);
        $response->assertJsonPath('message', __('messages.success.author.updated'));
        $response->assertJsonPath('author.id', $this->pathParameters['author']);
        $response->assertJsonPath('author.surname', $this->data['surname']);
        $response->assertJsonPath('author.name', $this->data['name']);
        $response->assertJsonPath('author.patronymic', $this->data['patronymic']);
    }

    /**
     * Сценарий обновления автора c отсутствующим отчеством
     *
     * @return void
     */
    public function test_author_update_with_empty_patronymic(): void
    {
        $this->loginAsUser();
        $this->data['patronymic'] = null;
        $response = parent::makePutJsonRequest();
        $response = parent::assertResponseStatusAsOk($response);
        $response->assertJsonStructure($this->getResponseJsonStructure());
        $responseData = $response->json();
        $this->assertIsString($responseData['message']);
        $this->assertIsArray($responseData['author']);
        $this->assertIsInt($responseData['author']['id']);
        $this->assertIsString($responseData['author']['surname']);
        $this->assertIsString($responseData['author']['name']);
        $patronymic = $responseData['author']['patronymic'];
        $this->assertNull($patronymic);
        $response->assertJsonPath('message', __('messages.success.author.updated'));
        $response->assertJsonPath('author.id', $this->pathParameters['author']);
        $response->assertJsonPath('author.surname', $this->data['surname']);
        $response->assertJsonPath('author.name', $this->data['name']);
        $response->assertJsonPath('author.patronymic', $this->data['patronymic']);
    }


    /**
     * Сценарий обновления автора без авторизации
     *
     * @return void
     */
    public function test_author_update_without_auth(): void
    {
        $response = parent::makePutJsonRequest();
        $response = parent::assertResponseStatusAsUnauthorized($response);
        $response->assertJson(['message' => __('messages.unauthenticated')]);
    }


    /**
     * Сценарий обновления автора с пустой фамилией
     *
     * @return void
     */
    public function test_author_update_with_empty_surname(): void
    {
        $this->loginAsUser();
        $this->data['surname'] = '';

        $response = parent::makePutJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['surname']);
    }

    /**
     * Сценарий обновления автора с пустым именем
     *
     * @return void
     */
    public function test_author_update_with_empty_name(): void
    {
        $this->loginAsUser();
        $this->data['name'] = '';

        $response = parent::makePutJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * Сценарий обновления автора с именем превышающем 100 символов
     *
     * @return void
     */
    public function test_author_update_with_name_exceeding_max_length(): void
    {
        $this->loginAsUser();
        $this->data['name'] = str_repeat('a', 101);
        $response = parent::makePutJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * Сценарий обновления автора с фамилией превышающем 100 символов
     *
     * @return void
     */
    public function test_author_update_with_surname_exceeding_max_length(): void
    {
        $this->loginAsUser();
        $this->data['surname'] = str_repeat('a', 101);
        $response = parent::makePutJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['surname']);
    }

    /**
     * Сценарий обновления автора с отчеством превышающем 100 символов
     *
     * @return void
     */
    public function test_author_update_with_patronymic_exceeding_max_length(): void
    {
        $this->loginAsUser();
        $this->data['patronymic'] = str_repeat('a', 101);
        $response = parent::makePutJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['patronymic']);
    }

    /**
     * Сценарий обновления автора с невалидным типом имени
     *
     * @return void
     */
    public function test_author_update_with_invalid_name_type(): void
    {
        $this->loginAsUser();
        $this->data['name'] = 111;
        $response = parent::makePutJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * Сценарий обновления автора с невалидным типом фамилии
     *
     * @return void
     */
    public function test_author_update_with_invalid_surname_type(): void
    {
        $this->loginAsUser();
        $this->data['surname'] = 111;
        $response = parent::makePutJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['surname']);
    }

    /**
     * Сценарий обновления автора с невалидным типом отчества
     *
     * @return void
     */
    public function test_author_update_with_invalid_patronymic_type(): void
    {
        $this->loginAsUser();
        $this->data['patronymic'] = 111;
        $response = parent::makePutJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['patronymic']);
    }
}
