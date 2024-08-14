<?php

namespace Tests\Feature;

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
        $bookIds = Book::query()->pluck('id');
        $randomBookId = $bookIds->random();
        $this->route = route('book.destroy', ['book' => $randomBookId]);
    }

    /**
     * Сценарий удаления книги
     *
     * @return void
     */
    public function test_book_delete()
    {
        $urlParts = parse_url($this->route);
        $pathParts = explode('/', $urlParts['path']);
        $bookId = (int)end($pathParts);
        $this->route = route('book.destroy', ['book' => $bookId]);
        $this->assertDatabaseHas('books', ['id' => $bookId]);
        $response = parent::makeDeleteJsonRequest();
        $this->assertSoftDeleted('books', ['id' => $bookId]);
        $response = parent::assertResponseStatusAsNoContent($response);
        $this->assertEmpty($response->getContent());
        $response = parent::makeDeleteJsonRequest();
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());

    }

    /**
     * Сценария, когда при удалении книги передается несуществующий ID книги
     *
     * @return void
     */
    public function test_book_not_found(): void
    {
        $maxBookId = Book::query()->max('id');
        $invalidBookId = $maxBookId + 1;
        $this->route = route('book.destroy', ['book' => $invalidBookId]);
        $response = parent::makeDeleteJsonRequest();
        $response = parent::assertResponseStatusAsNotFound($response);
        $response->assertJsonStructure(['message']);
        $response->assertJsonPath('message', __('exceptions.not_found.book'));
    }


    /**
     * @inheritdoc
     *
     * @param array $structure
     * @return void
     */
    protected function setResponseJsonStructure(array $structure): void
    {
    }
}
