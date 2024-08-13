<?php

namespace Tests;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\Traits\AssertDateFormat;

/**
 * Класс BaseFeatureTest
 *
 * Базовый класс для всех интеграционных тестов
 *
 * @package Tests\Feature
 * @extends TestCase
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 12.08.2024 9:02
 */
abstract class BaseFeatureTest extends TestCase
{
    use AssertDateFormat;

    /**
     * Данные тела запроса
     *
     * @var array
     */
    protected array $data = [];

    /**
     * Наименование маршрута
     *
     * @var string
     */
    protected string $route;

    /**
     * Массив HTTP заголовков
     *
     * @var array
     */
    protected array $headers = [];

    /**
     * Фейкер
     *
     * @var Generator
     */
    protected Generator $faker;

    /**
     * Устанавливает структуру JSON в ответе
     *
     * @param array $structure
     * @return void
     */
    abstract protected function setResponseJsonStructure(array $structure): void;

    /**
     * Настраивает тестовую среду перед каждым тестом.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->headers = ['Accept' => 'application/json'];
    }

    /**
     * Выполняет JSON POST-запрос к заданному маршруту с заданными данными и заголовками.
     *
     * @return TestResponse
     */
    protected function makePostJsonRequest(): TestResponse
    {
        return $this->postJson($this->route, $this->data, $this->headers);
    }

    /**
     * Выполняет JSON GET-запрос к заданному маршруту с заданными данными и заголовками.
     *
     * @return TestResponse
     */
    protected function makeGetJsonRequest(): TestResponse
    {
        return $this->getJson($this->route, $this->headers);
    }


    /**
     * Метод проверяет, что статус ответа соответствует "Created" (HTTP 201)
     *
     * @param TestResponse $response
     * @return TestResponse
     */
    protected function assertResponseStatusAsCreated(TestResponse $response): TestResponse
    {
        return $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * Метод проверяет, что статус ответа соответствует "Unprocessable Content" (HTTP 422)
     *
     * @param TestResponse $response
     * @return TestResponse
     */
    protected function assertResponseStatusAsUnprocessableEntity(TestResponse $response): TestResponse
    {
        return $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Метод проверяет, что статус ответа соответствует "Not Found" (HTTP 404)
     *
     * @param TestResponse $response
     * @return TestResponse
     */
    protected function assertResponseStatusAsNotFound(TestResponse $response): TestResponse
    {
        return $response->assertStatus(Response::HTTP_NOT_FOUND);

    }

    /**
     * Метод проверяет, что статус ответа соответствует "OK" (HTTP 200)
     *
     * @param TestResponse $response
     * @return TestResponse
     */
    protected function assertResponseStatusAsOk(TestResponse $response): TestResponse
    {
        return $response->assertStatus(Response::HTTP_OK);

    }

}
