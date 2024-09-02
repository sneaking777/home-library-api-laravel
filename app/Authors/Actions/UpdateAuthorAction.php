<?php

namespace App\Authors\Actions;

use App\Authors\Requests\AuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use App\Responders\BaseResponder;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Класс UpdateAuthorAction
 *
 * Класс предназначен для обработки запросов на обновление информации об авторе
 *
 * @package App\Authors\Actions
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 02.09.2024 11:25
 */
readonly class UpdateAuthorAction
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
     * @param Author $author
     * @return JsonResponse
     */
    public function __invoke(AuthorRequest $request, Author $author): JsonResponse
    {
        $author->update($request->validated());
        $result['data'] = [
            'message' => __('messages.success.author.updated'),
            'author' => new AuthorResource($author),

        ];
        $result['status'] = Response::HTTP_OK;

        return $this->responder->respond($result['data'], $result['status']);
    }
}
