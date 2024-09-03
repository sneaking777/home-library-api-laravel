<?php

namespace Tests;

use App\Models\User;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
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
class BaseFeatureTest extends TestCase
{
    use AssertDateFormat;
    use RefreshDatabase;

    /**
     * Структура JSON в ответе
     *
     * @var array
     */
    private array $responseJsonStructure;


    /**
     * Данные тела запроса
     *
     * @var array
     */
    protected array $data = [];

    /**
     * Массив для хранения параметров пути URL.
     * Параметры пути - это часть URL, которые идут после имени хоста и/или порта,
     * но перед строкой запроса (если она есть).
     * Каждый параметр представляет собой отдельный сегмент URL, разделенный слэшами (/).
     *
     * @var array
     */
    protected array $pathParameters = [];

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
    protected function setResponseJsonStructure(array $structure): void
    {
        $this->responseJsonStructure = $structure;
    }

    /**
     * Возвращает структуру JSON в ответе
     *
     * @return array
     */
    protected function getResponseJsonStructure(): array
    {
        return $this->responseJsonStructure;
    }

    /**
     * Настраивает тестовую среду перед каждым тестом.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        // TODO доработать установку локали для Faker
        $this->faker = Factory::create('ru_RU');
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
     * Выполняет JSON PUT-запрос к заданному маршруту с заданными данными и заголовками.
     *
     * @return TestResponse
     */
    protected function makePutJsonRequest(): TestResponse
    {
        return $this->putJson($this->route, $this->data, $this->headers);
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
     * Выполняет JSON DELETE-запрос к заданному маршруту с заданными данными и заголовками.
     *
     * @return TestResponse
     */
    protected function makeDeleteJsonRequest(): TestResponse
    {
        return $this->deleteJson($this->route, $this->headers);
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
     * Метод проверяет, что статус ответа соответствует "Unauthorized" (HTTP 401)
     *
     * @param TestResponse $response
     * @return TestResponse
     */
    protected function assertResponseStatusAsUnauthorized(TestResponse $response): TestResponse
    {
        return $response->assertStatus(Response::HTTP_UNAUTHORIZED);

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

    /**
     * Метод проверяет, что статус ответа соответствует "OK" (HTTP 204)
     *
     * @param TestResponse $response
     * @return TestResponse
     */
    public function assertResponseStatusAsNoContent(TestResponse $response): TestResponse
    {
        return $response->assertStatus(Response::HTTP_NO_CONTENT);
    }


    /**
     * Вход в систему под пользователем.
     * Метод создает новую учетную запись пользователя с помощью фабрики и устанавливает ее как текущую.
     *
     * @return User
     */
    protected function loginAsUser(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $this->assertTrue(Auth::check());

        return $user;
    }
}
