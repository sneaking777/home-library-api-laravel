<?php

namespace App\Books\Actions;

use App\Books\Requests\BookRequest;
use App\Http\Resources\BookResource;
use App\Models\Author;
use App\Models\Book;
use App\Responders\BaseResponder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Класс UpdateBookDocumentation
 *
 * Класс предназначен для обработки запросов на обновление информации о конкретной книге
 *
 * @package App\Books\Actions
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 13.08.2024 17:06
 */
readonly class UpdateBookAction
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
     * @param BookRequest $request
     * @param Book $book
     * @return JsonResponse
     */
    public function __invoke(BookRequest $request, Book $book): JsonResponse
    {
        try {
            Author::findOrFail($request->author_id);
            $book->update($request->validated());
            $result['data'] = [
                'message' => 'Книга успешно обновлена.',
                'book' => new BookResource($book->load('author')),

            ];
            $result['status'] = Response::HTTP_OK;

            return $this->responder->respond($result['data'], $result['status']);
        } catch (ModelNotFoundException $e) {
            //TODO $e - нужно добавить в логгер, который надо впоследствии создать. т.к. эта переменная нигде не используется, а это несовсем правило
            $result['data'] = ['error' => __('exceptions.not_found.author')];
            $result['status'] = Response::HTTP_NOT_FOUND;

            return $this->responder->respond($result['data'], $result['status']);
        }
    }

}
