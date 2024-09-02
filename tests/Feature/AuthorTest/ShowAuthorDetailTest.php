<?php

namespace AuthorTest;

use App\Models\Author;
use Tests\BaseFeatureTest;

/**
 * Класс ShowAuthorDetailTest
 *
 * Класс для тестирования просмотра информации о авторе.
 *
 * @extends BaseFeatureTest
 * @package AuthorTest
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 02.09.2024 10:19
 */
class ShowAuthorDetailTest extends BaseFeatureTest
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
                'data' => [
                    'id',
                    'surname',
                    'name',
                    'patronymic'
                ],
            ]
        );
    }

    /**
     * Сценарий по отображению данных конкретного автора
     *
     * @return void
     */
    public function test_show_author_detail(): void
    {
        $this->loginAsUser();
        $author = Author::factory()->create();
        $this->route = route('author.show', ['author' => $author->id]);
        $response = parent::makeGetJsonRequest();
        $response = parent::assertResponseStatusAsOk($response);
        $response->assertJsonStructure($this->getResponseJsonStructure());
        $response->assertJsonPath('data.id', $author->id);
        $responseData = $response->json();
        $this->assertIsArray($responseData['data']);
        $this->assertIsInt($responseData['data']['id']);
        $this->assertIsString($responseData['data']['surname']);
        $this->assertIsString($responseData['data']['name']);
        $this->assertIsString($responseData['data']['patronymic']);
        $patronymic = $responseData['data']['patronymic'];
        if ($patronymic !== null) {
            $this->assertIsString($patronymic);
        } else {
            $this->assertNull($patronymic);
        }
    }

    /**
     * Сценарий по отображению данных конкретного автора без авторизации
     *
     * @return void
     */
    public function test_show_author_detail_without_auth(): void
    {
        $author = Author::factory()->create();
        $this->route = route('author.show', ['author' => $author->id]);
        $response = parent::makeGetJsonRequest();
        $response = parent::assertResponseStatusAsUnauthorized($response);
        $response->assertJson(['message' => __('messages.unauthenticated')]);
    }

    /**
     * Сценария, когда при просмотре информации о авторе передается несуществующий ID автора
     *
     * @return void
     */
    public function test_author_not_found(): void
    {
        $this->loginAsUser();
        $author = Author::factory()->create();
        $invalidAuthorId = $author->id + 1;
        $this->route = route('author.show', ['author' => $invalidAuthorId]);
        $response = parent::makeGetJsonRequest();
        $response = parent::assertResponseStatusAsNotFound($response);
        $response->assertJsonStructure(['message']);
        $response->assertJsonPath('message', __('exceptions.not_found.author'));
    }

}
