<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
     * Сценарий создания книги
     *
     * @return void
     */
    public function test_book_creation(): void
    {
        $bookData = [
            'title' => 'Sample Books Title',
        ];

        $response = $this->post(
            'api/v1/book/create',
            $bookData,
            ['Accept' => 'application/json']);
        $response
            ->assertStatus(201)
            ->assertJson(['title' => 'Sample Books Title'], true)
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
        $this->assertDatabaseHas('books', $bookData);
    }

    /**
     * Сценарий создания книги с пустым заголовком
     *
     * @return void
     */
    public function test_book_creation_with_empty_title(): void
    {
        $bookData = [
            'title' => '',
        ];

        $response = $this->post(
            'api/v1/book/create',
            $bookData,
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    /**
     * Сценарий создание книги с заголовком, превышающим максимально допустимую длину
     *
     * @return void
     */
    public function test_book_creation_with_title_exceeding_max_length(): void
    {
        $bookData = [
            'title' => str_repeat('Sample Book Title ', 15),
        ];

        $response = $this->post(
            'api/v1/book/create',
            $bookData,
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }
}
