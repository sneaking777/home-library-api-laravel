<?php

namespace AuthorTest;

use App\Models\Author;
use Tests\BaseFeatureTest;

/**
 * Класс AuthorDeleteTest
 *
 * Тестовый класс для проверки удаления автора.
 *
 * Этот класс содержит набор тестовых сценариев
 * для проверки функционала, связанного с удалением авторов в приложении.
 *
 * Это может включать проверку на корректное удаление объектов автора,
 * обработку ошибок, а также связанные с этим изменения в БД и другое.
 *
 * @extends BaseFeatureTest
 * @package AuthorTest
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 03.09.2024 14:04
 */
class AuthorDeleteTest extends BaseFeatureTest
{
    /**
     * @inheritdoc
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->setResponseJsonStructure([]);
        $author = Author::factory()->create();
        $this->route = route('author.destroy', ['author' => $author->id]);
        $this->pathParameters['author'] = $author->id;
    }

    /**
     * Сценарий удаления автора
     *
     * @return void
     */
    public function test_author_delete()
    {
        $this->loginAsUser();
        $this->assertDatabaseHas('authors', ['id' => $this->pathParameters['author']]);
        $response = parent::makeDeleteJsonRequest();
        $this->assertSoftDeleted('authors', ['id' => $this->pathParameters['author']]);
        $response = parent::assertResponseStatusAsNoContent($response);
        $this->assertEmpty($response->getContent());
        $response = parent::makeDeleteJsonRequest();
        parent::assertResponseStatusAsNotFound($response);
    }

    /**
     * Сценарий удаления автора без авторизации
     *
     * @return void
     */
    public function test_author_delete_without_auth()
    {
        $this->assertDatabaseHas('authors', ['id' => $this->pathParameters['author']]);
        $response = parent::makeDeleteJsonRequest();
        $response = parent::assertResponseStatusAsUnauthorized($response);
        $response->assertJson(['message' => __('messages.unauthenticated')]);
    }

    /**
     * Сценария, когда при удалении автора передается несуществующий ID автора
     *
     * @return void
     */
    public function test_author_not_found(): void
    {
        $this->loginAsUser();
        $invalidAuthorId = Author::query()->max('id') + 1;
        $this->route = route('author.destroy', ['author' => $invalidAuthorId]);
        $response = parent::makeDeleteJsonRequest();
        $response = parent::assertResponseStatusAsNotFound($response);
        $response->assertJsonStructure(['message']);
        $response->assertJsonPath('message', __('exceptions.not_found.author'));
    }


}
