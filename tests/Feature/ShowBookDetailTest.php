<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Support\Arr;
use Tests\BaseFeatureTest;

/**
 * Класс ShowBookDetailTest
 *
 * Класс для тестирования информации о книге.
 *
 * Этот класс содержит методы для проверки следующего функционала:
 * - Возврат правильного JSON ответа при запросе деталей книги
 * - Соответствие возвращаемого идентификатора книги запрашиваемому
 * - Проверка существования запрашиваемой книги в БД
 * - Проверка корректности связи книги с ее автором
 * - Существование автора в базе данных
 * - Существование книги в базе данных
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
     * Структура JSON в ответе
     *
     * @var array
     */
    private array $responseJsonStructure;

    /**
     * Настраивает тестовую среду перед каждым тестом.
     * Устанавливает начальные данные для книги ($this->data).
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

    /**
     * Сценарий по отображению данных конкретной книги
     *
     * @return void
     */
    public function test_show_book_detail(): void
    {
        $bookIds = Book::query()->pluck('id')->toArray();
        $randomBookId = Arr::random($bookIds);
        $this->route = route('book.show', ['book' => $randomBookId]);
        $response = parent::makeGetJsonRequest();
        $response = parent::assertResponseStatusAsOk($response);
        $response->assertJsonStructure($this->responseJsonStructure);
        $response->assertJsonPath('data.id', $randomBookId);
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
     * Сценария, когда при просмотре книги передается несуществующий ID книги
     *
     * @return void
     */
    public function test_book_not_found(): void
    {
        $maxBookId = Book::query()->max('id');
        $invalidBookId = $maxBookId + 1;
        $this->route = route('book.show', ['book' => $invalidBookId]);
        $response = parent::makeGetJsonRequest();
        $response = parent::assertResponseStatusAsNotFound($response);
        $response->assertJsonStructure(['message']);
        $response->assertJsonPath('message', 'Запрашиваемая книга не найдена.');
    }
}
