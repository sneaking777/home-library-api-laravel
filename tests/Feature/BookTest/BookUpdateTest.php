<?php

namespace BookTest;


use App\Models\Author;
use App\Models\Book;
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
        $book = Book::factory()->create();
        $this->route = route('book.update', ['book' => $book->id]);
        $this->pathParameters['book'] = $book->id;
    }

    /**
     * Сценарий обновления конкретной книги
     *
     * @return void
     */
    public function test_book_update()
    {
        $this->loginAsUser();
        $book = Book::with('author')->find($this->pathParameters['book']);
        $this->data['author_id'] = $book->author->id;
        $response = parent::makePutJsonRequest();
        $response = parent::assertResponseStatusAsOk($response);
        $response->assertJsonStructure($this->getResponseJsonStructure());
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
        $response->assertJsonPath('message', __('messages.success.book.updated'));
        $response->assertJsonPath('book.id', $this->pathParameters['book']);
        $response->assertJsonPath('book.title', $this->data['title']);
        $response->assertJsonPath('book.author.id', $this->data['author_id']);
        $response->assertJsonPath('book.author.surname', $book->author->surname);
        $response->assertJsonPath('book.author.name', $book->author->name);
        $response->assertJsonPath('book.author.patronymic', $book->author->patronymic);
    }

    /**
     * Сценарий обновления книги без авторизации
     *
     * @return void
     */
    public function test_book_update_without_auth()
    {
        $this->route = route('book.update', ['book' => $this->pathParameters['book']]);
        $book = Book::with('author')->find($this->pathParameters['book']);
        $this->data['author_id'] = $book->author->id;
        $response = parent::makePutJsonRequest();
        $response = parent::assertResponseStatusAsUnauthorized($response);
        $response->assertJson(['message' => __('messages.unauthenticated')]);
    }

    /**
     * Сценарий обновления книги с пустым заголовком
     *
     * @return void
     */
    public function test_book_update_with_empty_title(): void
    {
        $this->loginAsUser();
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
        $this->loginAsUser();
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
        $this->loginAsUser();
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
     * Сценария, когда при обновлении книги передается несуществующий ID книги
     *
     * @return void
     */
    public function test_book_not_found(): void
    {
        $this->loginAsUser();
        $maxBookId = Book::query()->max('id');
        $invalidBookId = $maxBookId + 1;
        $this->route = route('book.update', ['book' => $invalidBookId]);
        $response = parent::makePutJsonRequest();
        $response = parent::assertResponseStatusAsNotFound($response);
        $response->assertJsonStructure(['message']);
        $response->assertJsonPath('message', __('exceptions.not_found.book'));
    }
}
