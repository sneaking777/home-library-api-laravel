<?php

namespace BookTest;

use App\Models\Author;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
     * @inheritdoc
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->setResponseJsonStructure([
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
        $this->loginAsUser();
        $authorsIds = Author::query()->pluck('id');

        if ($authorsIds->isEmpty()) {
            $author = Author::factory()->create();
            $this->data['author_id'] = $author->id;
        } else {
            $authorId = $authorsIds->random();
            $this->data['author_id'] = $authorId;
        }

        $response = parent::makePostJsonRequest();
        $this->assertBookFields($response);
    }

    /**
     * Сценарий создания книги без авторизации
     *
     * @return void
     */
    public function test_book_creation_without_auth(): void
    {
        $authorsIds = Author::query()->pluck('id');

        if ($authorsIds->isEmpty()) {
            $author = Author::factory()->create();
            $this->data['author_id'] = $author->id;
        } else {
            $authorId = $authorsIds->random();
            $this->data['author_id'] = $authorId;
        }
        $response = parent::makePostJsonRequest();
        $response = parent::assertResponseStatusAsUnauthorized($response);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    /**
     * Сценарий создания книги с пустым заголовком
     *
     * @return void
     */
    public function test_book_creation_with_empty_title(): void
    {
        $this->loginAsUser();
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
        $this->loginAsUser();
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
        $this->loginAsUser();
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
        $response = parent::assertResponseStatusAsCreated($response);
        $response = parent::assertResponseStatusAsCreated($response)
            ->assertJsonIsObject()
            ->assertJsonStructure($this->responseJsonStructure)
            ->assertJsonPath('message', 'Книга успешно создана.')
            ->assertJsonPath('book.title', $this->data['title'])
            ->assertJsonPath('book.author.id', $this->data['author_id']);
        $responseArray = json_decode($response->getContent(), true);
        $this->assertIsString($responseArray['message']);
        $this->assertIsArray($responseArray['book']);
        $this->assertIsNumeric($responseArray['book']['id']);
        $this->assertIsString($responseArray['book']['title']);
        $this->assertIsArray($responseArray['book']['author']);
        $this->assertIsNumeric($responseArray['book']['author']['id']);
        $this->assertIsString($responseArray['book']['author']['surname']);
        $this->assertIsString($responseArray['book']['author']['name']);
        $this->assertIsString($responseArray['book']['author']['patronymic']);
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
