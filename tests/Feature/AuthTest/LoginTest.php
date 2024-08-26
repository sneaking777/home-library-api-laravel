<?php

namespace AuthTest;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\BaseFeatureTest;

/**
 * Класс LoginTest
 *
 * Класс разработан для тестирования аутентификации пользователя в приложении.
 * Он содержит тесты для различных сценариев аутентификации, включая позитивные и негативные случаи.
 *
 * @extends BaseFeatureTest
 * @package AuthTest
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 25.08.2024 19:12
 */
class LoginTest extends BaseFeatureTest
{

    const EMAIL = 'john.doe@example.com';

    const INVALID_EMAIL = 'invalid.user@example.com';

    const PASSWORD = 'password';

    const INVALID_PASSWORD = 'invalid_password';


    /**
     * Структура JSON в ответе
     *
     * @var array
     */
    private array $responseJsonStructure;


    /**
     * @inheritdoc
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->setResponseJsonStructure([
            'access_token',
            'token_type'
        ]);
        $this->route = route('auth.login');
        $this->data = [
            'email' => self::EMAIL,
            'password' => self::PASSWORD,
        ];
    }

    /**
     * Сценарий авторизации пользователя в системе
     *
     * @return void
     */
    public function test_login(): void
    {
        User::factory()->create([
            'email' => self::EMAIL,
            'password' => Hash::make(self::PASSWORD),
        ]);
        $response = $this->makePostJsonRequest();
        $response = $this->assertResponseStatusAsOk($response);
        $response->assertJsonStructure($this->responseJsonStructure);
        $response->assertJson(['token_type' => 'Bearer']);
    }

    /**
     * Сценарий авторизации пользователя в системе с попыткой ввести невалидный email
     *
     * @return void
     */
    public function test_login_with_invalid_email(): void
    {
        User::factory()->create([
            'email' => self::EMAIL,
            'password' => Hash::make(self::PASSWORD),
        ]);
        $this->data = [
            'email' => self::INVALID_EMAIL,
            'password' => self::PASSWORD,
        ];
        $response = $this->makePostJsonRequest();
        $response = $this->assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJson([
            'message' => 'Предоставленные учетные данные неверны.',
            'errors' => [
                ['Предоставленные учетные данные неверны.']
            ]
        ]);
    }

    /**
     * Сценарий авторизации пользователя в системе с попыткой ввести невалидный пароль
     *
     * @return void
     */
    public function test_login_with_invalid_password(): void
    {
        User::factory()->create([
            'email' => self::EMAIL,
            'password' => Hash::make(self::PASSWORD),
        ]);
        $this->data = [
            'email' => self::EMAIL,
            'password' => self::INVALID_PASSWORD,
        ];
        $response = $this->makePostJsonRequest();
        $response = $this->assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJson([
            'message' => 'Предоставленные учетные данные неверны.',
            'errors' => [
                ['Предоставленные учетные данные неверны.']
            ]
        ]);
    }

    /**
     * Сценарий авторизации пользователя в системе с попыткой ввести пустой email
     *
     * @return void
     */
    public function test_login_with_empty_email(): void
    {
        $this->data['email'] = '';
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['email']);
    }

    /**
     * Сценарий авторизации пользователя в системе с попыткой ввести пустой пароль
     *
     * @return void
     */
    public function test_login_with_empty_password(): void
    {
        $this->data['password'] = '';
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['password']);
    }


    /**
     * @inheritdoc
     *
     * @param array $structure
     * @return void
     */
    protected function setResponseJsonStructure(array $structure): void
    {
        $this->responseJsonStructure = $structure;
    }
}
