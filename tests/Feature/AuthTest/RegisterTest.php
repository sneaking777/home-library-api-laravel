<?php

namespace AuthTest;

use App\Models\User;
use Tests\BaseFeatureTest;

/**
 * Класс RegisterTest
 *
 * Тестовый класс для проверки функциональности регистрации
 *
 * @extends BaseFeatureTest
 * @package AuthTest
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 26.08.2024 9:02
 */
class RegisterTest extends BaseFeatureTest
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->setResponseJsonStructure([
            'name',
            'email',
            'id'
        ]);
        $this->route = route('auth.register');
        $this->data = [
            'name' => "John Doe",
            'email' => "john@example.com",
            'password' => "123456",
            'password_confirmation' => "123456"
        ];
    }

    /**
     * Сценарий регистрации пользователя
     *
     * @return void
     */
    public function test_register(): void
    {
        $response = $this->makePostJsonRequest();
        $response = $this->assertResponseStatusAsCreated($response);
        $response->assertJsonIsObject()->assertJsonStructure($this->getResponseJsonStructure());
        $responseArray = json_decode($response->getContent(), true);
        $this->assertIsString($responseArray['name']);
        $this->assertIsString($responseArray['email']);
        $this->assertNotFalse(filter_var($responseArray['email'], FILTER_VALIDATE_EMAIL));
        $this->assertIsInt($responseArray['id']);
        $this->assertDatabaseHas('users', [
            'email' => $responseArray['email'],
            'name' => $responseArray['name'],
        ]);
        $response->assertJson(['name' => 'John Doe']);
        $response->assertJson(['email' => 'john@example.com']);
    }

    /**
     * Сценарий регистрации пользователя в системе с попыткой ввести пустой email
     *
     * @return void
     */
    public function test_register_with_empty_email(): void
    {
        $this->data['email'] = '';
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['email']);
    }

    /**
     * Сценарий регистрации пользователя в системе с попыткой ввести пустой пароль
     *
     * @return void
     */
    public function test_register_with_empty_password(): void
    {
        $this->data['password'] = '';
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['password']);
    }

    /**
     * Сценарий регистрации пользователя в системе с попыткой ввести пустое имя
     *
     * @return void
     */
    public function test_register_with_empty_name(): void
    {
        $this->data['name'] = '';
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * Сценарий регистрации пользователя в системе с попыткой ввести email не являющийся строкой
     *
     * @return void
     */
    public function test_register_with_non_string_email(): void
    {
        $this->data['email'] = 1234;
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['email']);
    }

    /**
     * Сценарий регистрации пользователя в системе с попыткой ввести пароль не являющийся строкой
     *
     * @return void
     */
    public function test_register_with_non_string_password(): void
    {
        $this->data['password'] = 1234;
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['password']);
    }

    /**
     * Сценарий регистрации пользователя в системе с попыткой ввести имя не являющееся строкой
     *
     * @return void
     */
    public function test_register_with_non_string_name(): void
    {
        $this->data['name'] = 1234;
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * Сценарий регистрации пользователя с именем пользователя больше 255 символов
     *
     * @return void
     */
    public function test_register_with_name_exceeding_max_length(): void
    {
        $this->data['name'] = str_repeat('a', 256);
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * Сценарий регистрации пользователя с невалидным email
     *
     * @return void
     */
    public function test_register_with_invalid_email(): void
    {
        $this->data['email'] = 'invalid email';
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['email']);
    }

    /**
     * Сценарий регистрации пользователя с адресом электронной почты, превышающим 255 символов
     *
     * @return void
     */
    public function test_register_with_email_exceeding_max_length(): void
    {
        $this->data['email'] = str_repeat('a', 246) . '@example.com';
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['email']);
    }

    /**
     * Сценарий регистрации пользователя с уже зарегистрированным email адресом
     *
     * @return void
     */
    public function test_register_with_duplicate_email(): void
    {
        User::query()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
        ]);

        // Попытка создания второго пользователя с тем же email адресом
        $this->data['email'] = 'johndoe@example.com';
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['email']);
    }

    /**
     * Сценарий регистрации пользователя с паролем, длина которого меньше 6 символов
     *
     * @return void
     */
    public function test_register_with_short_password(): void
    {
        $this->data['password'] = '12345';
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['password']);
    }

    /**
     * Сценарий регистрации пользователя с паролем, который не подтвержден
     *
     * @return void
     */
    public function test_register_with_unconfirmed_password(): void
    {
        $this->data['password'] = 'password';
        $this->data['password_confirmation'] = 'different_password';
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['password']);
    }
}
