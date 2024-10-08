<?php

namespace App\Authors\Actions;

use App\Authors\Requests\AuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use App\Responders\BaseResponder;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Класс CreateAuthorDocumentation
 *
 * Класс предназначен для создания нового автора в приложении.
 * В частности, он обрабатывает запросы на создание новых авторов и сохраняет их в хранилище данных
 *
 * @package App\Authors\Actions
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 01.09.2024 10:11
 */
readonly class CreateAuthorAction
{
    /**
     * @var BaseResponder
     */
    private BaseResponder $responder;

    /**
     * Конструктор
     *
     * @param BaseResponder $responder
     */
    public function __construct(BaseResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Призыватель
     *
     * @param AuthorRequest $request
     * @return JsonResponse
     */
    public function __invoke(AuthorRequest $request): JsonResponse
    {
        $author = new Author();
        $author->surname = $request->surname;
        $author->name = $request->name;
        $author->patronymic = $request->patronymic;

        $author->save();
        $result['data'] = [
            'message' => 'Автор успешно создан.',
            'author' => new AuthorResource($author),

        ];
        $result['status'] = Response::HTTP_CREATED;

        return $this->responder->respond($result['data'], $result['status']);
    }
}
