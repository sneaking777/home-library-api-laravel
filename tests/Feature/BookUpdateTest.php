<?php

namespace Tests\Feature;


use App\Models\Author;
use App\Models\Book;
use Illuminate\Support\Arr;
use Tests\BaseFeatureTest;

/**
 * Класс BookUpdateTest
 *
 * Этот класс содержит набор тестов для проверки функциональности обновления книги.
 * С его помощью проверяется корректность работы операции обновления книги в приложении.
 *
 * @extends BaseFeatureTest
 * @package Tests\Feature
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 14.08.2024 14:49
 */
class BookUpdateTest extends BaseFeatureTest
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
        $this->setResponseJsonStructure(
            [
                'message',
                'book' => [
                    'id',
                    'title',
                    'author' => [
                        'id',
                        'surname',
                        'name',
                        'patronymic'
                    ]
                ]
            ]
        );
        $this->data = [
            'title' => $this->faker->sentence(3),
        ];
        $bookIds = Book::query()->pluck('id')->toArray();
        $randomBookId = Arr::random($bookIds);
        $this->route = route('book.update', ['book' => $randomBookId]);
    }

    /**
     * Сценарий обновления конкретной книги
     *
     * @return void
     */
    public function test_book_update()
    {
        $urlParts = parse_url($this->route);
        $pathParts = explode('/', $urlParts['path']);
        $bookId = (int)end($pathParts);
        $this->route = route('book.update', ['book' => $bookId]);
        $book = Book::with('author')->find($bookId);
        $this->data['author_id'] = $book->author->id;
        $response = parent::makePutJsonRequest();
        $response = parent::assertResponseStatusAsOk($response);
        $response->assertJsonStructure($this->responseJsonStructure);
        $responseData = $response->json();
        $this->assertIsString($responseData['message']);
        $this->assertIsArray($responseData['book']);
        $this->assertIsInt($responseData['book']['id']);
        $this->assertIsString($responseData['book']['title']);
        $this->assertIsArray($responseData['book']['author']);
        $this->assertIsInt($responseData['book']['author']['id']);
        $this->assertIsString($responseData['book']['author']['surname']);
        $this->assertIsString($responseData['book']['author']['name']);
        $patronymic = $responseData['book']['author']['patronymic'];
        if ($patronymic !== null) {
            $this->assertIsString($patronymic);
        } else {
            $this->assertNull($patronymic);
        }
        $response->assertJsonPath('message', 'Книга успешно обновлена');
        $response->assertJsonPath('book.id', $bookId);
        $response->assertJsonPath('book.title', $this->data['title']);
        $response->assertJsonPath('book.author.id', $this->data['author_id']);
        $response->assertJsonPath('book.author.surname', $book->author->surname);
        $response->assertJsonPath('book.author.name', $book->author->name);
        $response->assertJsonPath('book.author.patronymic', $book->author->patronymic);
    }

    /**
     * Сценарий обновления книги с пустым заголовком
     *
     * @return void
     */
    public function test_book_update_with_empty_title(): void
    {
        $this->data['title'] = '';
        $response = parent::makePutJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['title']);
    }

    /**
     * Сценарий обновления книги с заголовком, превышающим максимально допустимую длину
     *
     * @return void
     */
    public function test_book_update_with_title_exceeding_max_length(): void
    {
        $this->data['title'] = str_repeat('Sample Book Title ', 15);

        $response = parent::makePutJsonRequest();

        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['title']);
    }

    /**
     * Сценария, когда при обновлении книги передается несуществующий ID автора
     *
     * @return void
     */
    public function test_book_update_with_invalid_author_id(): void
    {
        $maxAuthorId = Author::query()->max('id');
        $this->data['author_id'] = $maxAuthorId + 1;
        $response = parent::makePutJsonRequest();
        $response = parent::assertResponseStatusAsNotFound($response);

        $response
            ->assertJson(['error' => __('exceptions.not_found.author')], true)
            ->assertJsonIsObject()
            ->assertJsonStructure(['error']);
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
