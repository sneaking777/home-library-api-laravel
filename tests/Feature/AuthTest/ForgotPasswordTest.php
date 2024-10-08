<?php

namespace AuthTest;

use App\Models\User;
use Tests\BaseFeatureTest;

/**
 * Класс BaseFeatureTest
 *
 * Тестовый класс для функционала восстановления пароля в приложении
 * Проверяет что, когда пользователь запросит сброс пароля, он получит
 * электронное письмо со ссылкой на сброс пароля и после перехода по ссылке
 * сможет успешно сбросить пароль.
 *
 * @extends BaseFeatureTest
 * @package AuthTest
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 29.08.2024 10:25
 */
class ForgotPasswordTest extends BaseFeatureTest
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
        ]);
        $this->route = route('auth.forgot');
        $this->data = [
            'email' => 'john@example.com',
        ];
    }

    /**
     * Сценарий успешного запроса пользователя на восстановление пароля
     *
     * @return void
     */
    public function test_forgot_password()
    {
        User::factory()->create(['email' => 'john@example.com']);
        $response = $this->makePostJsonRequest();
        $response = $this->assertResponseStatusAsOk($response);
        $response->assertJsonStructure($this->getResponseJsonStructure());
        $response->assertJson([
            'message' => __('messages.password_reset'),
        ]);
    }

    /**
     * Сценарий когда пользователь был не найден при запросе на восстановление пароля
     *
     * @return void
     */
    public function test_forgot_password_user_not_found()
    {
        $response = $this->makePostJsonRequest();
        $response = $this->assertResponseStatusAsNotFound($response);
        $response->assertJsonStructure($this->getResponseJsonStructure());
        $response->assertJson([
            'message' => __('messages.not_found.user'),
        ]);
    }
}
