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
 * Класс CreateBookAction
 *
 * Класс предназначен для создания новой книги в приложении.
 * В частности, он обрабатывает запросы на создание новых книг и сохраняет их в хранилище данных
 *
 * @package App\Books\Actions
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 11.08.2024 22:54
 */
readonly class CreateBookAction
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
     * @return JsonResponse
     */
    public function __invoke(BookRequest $request): JsonResponse
    {
        try {
            Author::findOrFail($request->author_id);
            $book = new Book;
            $book->title = $request->title;
            $book->author_id = $request->author_id;
            $book->save();
            $result['data'] = [
                'message' => 'Книга успешно создана.',
                'book' => new BookResource($book->load('author')),

            ];
            $result['status'] = Response::HTTP_CREATED;

            return $this->responder->respond($result['data'], $result['status']);

        } catch (ModelNotFoundException $e) {
            //TODO $e - нужно добавить в логгер, который надо впоследствии создать. т.к. эта переменная нигде не используется, а это несовсем правило
            $result['data'] = ['error' => __('exceptions.not_found.author')];
            $result['status'] = Response::HTTP_NOT_FOUND;

            return $this->responder->respond($result['data'], $result['status']);
        }
    }
}
