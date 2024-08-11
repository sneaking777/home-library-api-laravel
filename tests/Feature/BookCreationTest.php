<?php

namespace Tests\Feature;

use App\Models\Author;
use Faker\Factory;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Traits\AssertDateFormat;

/**
 * Класс BookCreationTest
 *
 * Этот класс содержит набор тестовых сценариев относящихся к процессу создания книг в нашем приложении.
 * Он включает в себя тесты, которые проверяют различные аспекты и возможные граничные случаи создания книг,
 * такие как корректность передаваемых данных, уникальность названия книги,
 * ограничения длины для названия книги и другие бизнес-правила.
 *
 * @package Tests\Feature
 * @extends TestCase
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 10.08.2024 15:16
 */
class BookCreationTest extends TestCase
{
    use AssertDateFormat;

    /**
     * @var array начальные данные для книги
     */
    private array $bookData;

    /**
     * Наименование маршрута
     *
     * @var string
     */
    private string $route;

    /**
     * Настраивает тестовую среду перед каждым тестом.
     * Устанавливает начальные данные для книги ($this->bookData).
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->route = route('book.store');
        $faker = Factory::create();
        $this->bookData = [
            'title' => $faker->sentence(3),
            'author_id' => $faker->numberBetween(),
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
        $this->bookData['author_id'] = $author->id;

        $response = $this->post(
            $this->route,
            $this->bookData,
            ['Accept' => 'application/json']);
        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson(['title' => $this->bookData['title']], true)
            ->assertJsonIsObject()
            ->assertJsonStructure([
                'title',
                'updated_at',
                'created_at',
                'id'
            ]);
        $responseArray = json_decode($response->getContent(), true);
        $this->assertIsString($responseArray['title']);
        $this->assertIsString($responseArray['updated_at']);
        $this->assertDateFormat($responseArray['created_at'], 'Y-m-d\TH:i:s.u\Z');
        $this->assertDateFormat($responseArray['updated_at'], 'Y-m-d\TH:i:s.u\Z');
        $this->assertIsString($responseArray['created_at']);
        $this->assertIsNumeric($responseArray['id']);
        $this->assertDatabaseHas('books', [
            'title' => $this->bookData['title'],
            'author_id' => $this->bookData['author_id']
        ]);
    }

    /**
     * Сценарий создания книги с пустым заголовком
     *
     * @return void
     */
    public function test_book_creation_with_empty_title(): void
    {
        $this->bookData['title'] = '';

        $response = $this->post(
            $this->route,
            $this->bookData,
            ['Accept' => 'application/json']
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['title']);
    }

    /**
     * Сценарий создание книги с заголовком, превышающим максимально допустимую длину
     *
     * @return void
     */
    public function test_book_creation_with_title_exceeding_max_length(): void
    {
        $this->bookData['title'] = str_repeat('Sample Book Title ', 15);

        $response = $this->post(
            $this->route,
            $this->bookData,
            ['Accept' => 'application/json']
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
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
        $invalidAuthorId = $maxAuthorId + 1;
        $this->bookData['author_id'] = $invalidAuthorId;
        $response = $this->post(
            $this->route,
            $this->bookData,
            ['Accept' => 'application/json']
        );

        $response
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['error' => __('exceptions.not_found.author')], true)
            ->assertJsonIsObject()
            ->assertJsonStructure(['error']);
    }
}
