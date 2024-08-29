<?php

namespace AuthTest;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Tests\BaseFeatureTest;

/**
 * Класс ResetPasswordTest
 *
 * ResetPasswordTest используется для тестирования валидности и функциональности
 * механизма сброса пароля в приложении. Он включает методы для проверки
 * как положительных, так и отрицательных тестовых случаев.
 *
 * @extends BaseFeatureTest
 * @package AuthTest
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 29.08.2024 11:38
 */
class ResetPasswordTest extends BaseFeatureTest
{
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
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => "123456",
        ]);
        $token = Password::getRepository()->create($user);

        $this->setResponseJsonStructure([
            'message',
        ]);
        $this->route = route('auth.reset');
        $this->data = [
            'email' => 'john@example.com',
            'token' => $token,
            'password' => "123457",
            'password_confirmation' => '123457'
        ];
    }

    /**
     * Сценарий успешного сброса и получения нового пароля
     *
     * @return void
     */
    public function test_reset_password(): void
    {
        $response = $this->makePostJsonRequest();
        $response = $this->assertResponseStatusAsOk($response);
        $response->assertJsonStructure($this->responseJsonStructure);
        $response->assertJson([
            'message' => __('messages.new_password'),
        ]);
    }

    /**
     * Сценарий, когда был передан неверный токен на сброс пароля
     *
     * @return void
     */
    public function test_reset_password_wrong_token(): void
    {
        $this->data['token'] = 'wrong-token';
        $this->setResponseJsonStructure([
            'error',
        ]);
        $response = $this->makePostJsonRequest();
        $response = $this->assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonStructure($this->responseJsonStructure);
        $response->assertJson([
            'error' => __('errors.password_reset'),
        ]);
    }

    /**
     * Сценарий, когда не был передан token на запрос сброса пароля
     *
     * @return void
     */
    public function test_reset_password_requires_token(): void
    {
        $this->data['token'] = '';
        $this->setResponseJsonStructure([
            'message',
            'errors' => [
                'token'
            ]
        ]);
        $response = $this->makePostJsonRequest();
        $response = $this->assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonStructure($this->responseJsonStructure);
        $response->assertJsonValidationErrors('token');

    }

    /**
     * Сценарий, когда не был передан email на запрос сброса пароля
     *
     * @return void
     */
    public function test_reset_password_requires_email(): void
    {
        $this->data['email'] = '';
        $this->setResponseJsonStructure([
            'message',
            'errors' => [
                'email'
            ]
        ]);
        $response = $this->makePostJsonRequest();
        $response = $this->assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonStructure($this->responseJsonStructure);
        $response->assertJsonValidationErrors('email');
    }

    /**
     * Сценарий, когда был передан невалидный email на запрос сброса пароля
     *
     * @return void
     */
    public function test_reset_password_requires_valid_email(): void
    {
        $this->data['email'] = 'not-an-email';
        $this->setResponseJsonStructure([
            'message',
            'errors' => [
                'email'
            ]
        ]);
        $response = $this->makePostJsonRequest();
        $response = $this->assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonStructure($this->responseJsonStructure);
        $response->assertJsonValidationErrors('email');
    }

    /**
     * Сценарий, когда был email превышает допустимую длину на запрос сброса пароля
     *
     * @return void
     */
    public function test_reset_password_fails_with_long_email(): void
    {
        $longEmail = str_repeat('a', 256) . '@example.com';
        $this->data['email'] = $longEmail;
        $this->setResponseJsonStructure([
            'message',
            'errors' => [
                'email'
            ]
        ]);
        $response = $this->makePostJsonRequest();
        $response = $this->assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonStructure($this->responseJsonStructure);
        $response->assertJsonValidationErrors('email');
    }

    /**
     * Сценарий, когда не был передан пароль на запрос сброса пароля
     *
     * @return void
     */
    public function test_reset_password_requires_password(): void
    {
        $this->data['password'] = '';
        $this->setResponseJsonStructure([
            'message',
            'errors' => [
                'password'
            ]
        ]);
        $response = $this->makePostJsonRequest();
        $response = $this->assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonStructure($this->responseJsonStructure);
        $response->assertJsonValidationErrors('password');
    }

    /**
     * Сценарий, когда не был подтвержден пароль на запрос сброса пароля
     *
     * @return void
     */
    public function test_reset_password_requires_password_confirmation(): void
    {
        $this->data['password_confirmation'] = '';
        $this->setResponseJsonStructure([
            'message',
            'errors' => [
                'password'
            ]
        ]);
        $response = $this->makePostJsonRequest();
        $response = $this->assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonStructure($this->responseJsonStructure);
        $response->assertJsonValidationErrors('password');
    }

    /**
     * Сценарий, когда был был оправлен короткий пароль на запрос сброса пароля
     *
     * @return void
     */
    public function test_reset_password_fails_with_small_password(): void
    {
        $this->data['password'] = 'abc';
        $this->data['password_confirmation'] = 'abc';
        $this->setResponseJsonStructure([
            'message',
            'errors' => [
                'password'
            ]
        ]);
        $response = $this->makePostJsonRequest();
        $response = $this->assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonStructure($this->responseJsonStructure);
        $response->assertJsonValidationErrors('password');
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
