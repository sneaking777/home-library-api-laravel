<?php

namespace Tests\Feature;

use App\Models\Author;
use Illuminate\Testing\TestResponse;
use Tests\BaseFeatureTest;

/**
 * Класс BookCreationTest
 *
 * Этот класс содержит набор тестовых сценариев относящихся к процессу создания книг в нашем приложении.
 * Он включает в себя тесты, которые проверяют различные аспекты и возможные граничные случаи создания книг,
 * такие как корректность передаваемых данных, уникальность названия книги,
 * ограничения длины для названия книги и другие бизнес-правила.
 *
 * @package Tests\Feature
 * @extends BaseFeatureTest
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 10.08.2024 15:16
 */
class BookCreationTest extends BaseFeatureTest
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
        $this->setResponseJsonStructure([
            'title',
            'updated_at',
            'created_at',
            'id'
        ]);
        $this->route = route('book.store');
        $this->data = [
            'title' => $this->faker->sentence(3),
        ];
    }


    /**
     * Сценарий создания книги
     *
     * @return void
     */
    public function test_book_creation(): void
    {
        $author = Author::factory()->create();
        $this->data['author_id'] = $author->id;

        $response = parent::makePostJsonRequest();
        $this->assertBookFields($response);
    }

    /**
     * Сценарий создания книги с пустым заголовком
     *
     * @return void
     */
    public function test_book_creation_with_empty_title(): void
    {
        $this->data['title'] = '';

        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['title']);
    }

    /**
     * Сценарий создание книги с заголовком, превышающим максимально допустимую длину
     *
     * @return void
     */
    public function test_book_creation_with_title_exceeding_max_length(): void
    {
        $this->data['title'] = str_repeat('Sample Book Title ', 15);

        $response = parent::makePostJsonRequest();

        $response = parent::assertResponseStatusAsUnprocessableEntity($response);
        $response->assertJsonValidationErrors(['title']);
    }

    /**
     * Сценария, когда при создании книги передается несуществующий ID автора
     *
     * @return void
     */
    public function test_book_creation_with_invalid_author_id(): void
    {
        $maxAuthorId = Author::query()->max('id');
        $this->data['author_id'] = $maxAuthorId + 1;
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsNotFound($response);

        $response
            ->assertJson(['error' => __('exceptions.not_found.author')], true)
            ->assertJsonIsObject()
            ->assertJsonStructure(['error']);
    }

    /**
     * Выполняет проверку полей в ответе после создания книги.
     *
     * @param TestResponse $response
     * @return void
     */
    private function assertBookFields(TestResponse $response): void
    {
        $response = parent::assertResponseStatusAsCreated($response)
            ->assertJsonIsObject()
            ->assertJsonStructure($this->responseJsonStructure)
            ->assertJson(['title' => $this->data['title']], true);
        $responseArray = json_decode($response->getContent(), true);
        $this->assertIsString($responseArray['title']);
        $this->assertIsString($responseArray['updated_at']);
        $this->assertDateFormat($responseArray['created_at'], 'Y-m-d\TH:i:s.u\Z');
        $this->assertDateFormat($responseArray['updated_at'], 'Y-m-d\TH:i:s.u\Z');
        $this->assertIsString($responseArray['created_at']);
        $this->assertIsNumeric($responseArray['id']);
        $this->assertDatabaseHas('books', [
            'title' => $this->data['title'],
            'author_id' => $this->data['author_id']
        ]);
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
