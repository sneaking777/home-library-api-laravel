<?php

namespace BookTest;

use App\Models\Book;
use Symfony\Component\HttpFoundation\Response;
use Tests\BaseFeatureTest;

/**
 *
 * Класс BookDeleteTest
 *
 * Тестирование функционала удаления книги
 * Включает в себя проверку различных сценариев, таких как:
 * - успешное удаление книги;
 * - попытка удалить уже удаленную книгу;
 * - попытка удалить несуществующую книгу;
 *
 * @extends BaseFeatureTest
 * @package Tests\Feature
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 14.08.2024 16:29
 */
class BookDeleteTest extends BaseFeatureTest
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
        $book = Book::factory()->create();
        $this->route = route('book.destroy', ['book' => $book->id]);
        $this->pathParameters['book'] = $book->id;

    }

    /**
     * Сценарий удаления книги
     *
     * @return void
     */
    public function test_book_delete()
    {
        $this->loginAsUser();
        $this->assertDatabaseHas('books', ['id' => $this->pathParameters['book']]);
        $response = parent::makeDeleteJsonRequest();
        $this->assertSoftDeleted('books', ['id' => $this->pathParameters['book']]);
        $response = parent::assertResponseStatusAsNoContent($response);
        $this->assertEmpty($response->getContent());
        $response = parent::makeDeleteJsonRequest();
        parent::assertResponseStatusAsNotFound($response);

    }

    /**
     * Сценарий удаления книги без авторизации
     *
     * @return void
     */
    public function test_book_delete_without_auth()
    {
        $this->assertDatabaseHas('books', ['id' => $this->pathParameters['book']]);
        $response = parent::makeDeleteJsonRequest();
        $response = parent::assertResponseStatusAsUnauthorized($response);
        $response->assertJson(['message' => __('messages.unauthenticated')]);
    }

    /**
     * Сценария, когда при удалении книги передается несуществующий ID книги
     *
     * @return void
     */
    public function test_book_not_found(): void
    {
        $this->loginAsUser();
        $maxBookId = Book::query()->max('id');
        $invalidBookId = $maxBookId + 1;
        $this->route = route('book.destroy', ['book' => $invalidBookId]);
        $response = parent::makeDeleteJsonRequest();
        $response = parent::assertResponseStatusAsNotFound($response);
        $response->assertJsonStructure(['message']);
        $response->assertJsonPath('message', __('exceptions.not_found.book'));
    }
}
