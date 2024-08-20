<?php

namespace App\Swagger\Books;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;


#[
    OA\Delete(
        path: '/book/{book}',
        description: 'Запрос на удаление конкретной книги.',
        summary: 'Введите идентификатор книги',
        tags: ['book'],
        parameters: [
            new OA\Parameter(
                ref: "#/components/parameters/book",
            )
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_NO_CONTENT,
                description: 'No Content'
            ),
            new OA\Response(
                ref: "#/components/responses/book_not_found",
                response: Response::HTTP_NOT_FOUND,
            )
        ]
    )
]
class DeleteBookAction
{

}
