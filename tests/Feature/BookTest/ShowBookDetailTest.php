<?php

namespace BookTest;

use App\Models\Author;
use App\Models\Book;
use Tests\BaseFeatureTest;

/**
 * Класс ShowBookDetailTest
 *
 * Класс для тестирования просмотра информации о книге.
 *
 * Этот класс содержит методы для проверки следующего функционала:
 * - Возврат правильного JSON ответа при запросе деталей книги
 * - Соответствие возвращаемого идентификатора книги запрашиваемому
 * - Проверка существования запрашиваемой книги в БД
 * - Проверка корректности связи книги с ее автором
 * - Существование автора в базе данных
 * - Существование книги в базе данных
 * - Проверка пользователя на авторизацию
 *
 *
 * @extends BaseFeatureTest
 * @package Tests\Feature
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 13.08.2024 14:42
 */
class ShowBookDetailTest extends BaseFeatureTest
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
                    'title',
                    'author' => [
                        'id',
                        'surname',
                        'name',
                        'patronymic'
                    ]

                ],
            ]
        );
        $book = Book::factory()->create();
        $this->route = route('book.show', ['book' => $book->id]);
        $this->pathParameters['book'] = $book->id;
    }

    /**
     * Сценарий по отображению данных конкретной книги
     *
     * @return void
     */
    public function test_show_book_detail(): void
    {
        $this->loginAsUser();
        $response = parent::makeGetJsonRequest();
        $response = parent::assertResponseStatusAsOk($response);
        $response->assertJsonStructure($this->getResponseJsonStructure());
        $response->assertJsonPath('data.id', $this->pathParameters['book']);
        $responseData = $response->json();
        $this->assertIsArray($responseData['data']);
        $this->assertIsInt($responseData['data']['id']);
        $this->assertIsString($responseData['data']['title']);
        $this->assertIsArray($responseData['data']['author']);
        $this->assertIsInt($responseData['data']['author']['id']);
        $this->assertIsString($responseData['data']['author']['surname']);
        $this->assertIsString($responseData['data']['author']['name']);
        $patronymic = $responseData['data']['author']['patronymic'];
        if ($patronymic !== null) {
            $this->assertIsString($patronymic);
        } else {
            $this->assertNull($patronymic);
        }
        $bookId = $responseData['data']['id'];
        $authorId = $responseData['data']['author']['id'];
        $book = Book::query()->find($bookId);
        $this->assertNotNull(
            $book,
            "Книга не найдена в базе данных");
        $this->assertEquals(
            $authorId,
            $book->author_id,
            "Автор в ответе не соответствует автору книги в базе данных");
        $author = Author::query()->find($authorId);
        $this->assertNotNull($author, "Автор не найден в базе данных");
    }

    /**
     * Сценарий просмотра информации о книге без авторизации
     *
     * @return void
     */
    public function test_show_book_detail_without_auth()
    {
        $response = parent::makeGetJsonRequest();
        $response = parent::assertResponseStatusAsUnauthorized($response);
        $response->assertJson(['message' => __('messages.unauthenticated')]);
    }

    /**
     * Сценария, когда при просмотре книги передается несуществующий ID книги
     *
     * @return void
     */
    public function test_book_not_found(): void
    {
        $this->loginAsUser();
        $book = Book::factory()->create();
        $invalidBookId = $book->id + 1;
        $this->route = route('book.show', ['book' => $invalidBookId]);
        $response = parent::makeGetJsonRequest();
        $response = parent::assertResponseStatusAsNotFound($response);
        $response->assertJsonStructure(['message']);
        $response->assertJsonPath('message', __('exceptions.not_found.book'));
    }
}
