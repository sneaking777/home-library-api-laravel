<?php

namespace AuthTest;

use App\Models\User;
use Tests\BaseFeatureTest;

/**
 * Класс LogoutTest
 *
 * Этот класс содержит тесты, которые проверяют корректность работы процесса выхода
 * из системы в вашем приложении.
 *
 * @package AuthTest
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 27.08.2024 4:54
 */
class LogoutTest extends BaseFeatureTest
{
    /**
     * @inheritdoc
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->route = route('auth.logout');
    }

    /**
     * Сценарий выхода пользователя из системы
     *
     * @return void
     */
    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $this->assertInstanceOf(User::class, $user);
        $authToken = $user->createToken('test-token');
        $user->withAccessToken($authToken->accessToken);
        $this->actingAs($user, 'sanctum');
        $this->assertDatabaseHas('personal_access_tokens', [
            'token' => $authToken->accessToken->token
        ]);
        $response = $this->makeDeleteJsonRequest();
        $response = $this->assertResponseStatusAsOk($response);
        $response->assertJson(['message' => __('messages.success.logout')]);
        $this->assertDatabaseMissing('personal_access_tokens',
            [
                'token' => $authToken->accessToken->token
            ]
        );
    }

    /**
     * Сценарий проверяет сможет ли гость зайти в приложение будучи неавторизованным пользователем
     *
     * @return void
     */
    public function test_guest_cannot_logout()
    {
        $response = $this->makeDeleteJsonRequest();
        $response = $this->assertResponseStatusAsUnauthorized($response);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }
}
