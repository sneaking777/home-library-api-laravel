<?php

namespace App\Swagger\Authors;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;


#[
    OA\Delete(
        path: '/author/{author}',
        description: 'Запрос на удаление автора.',
        summary: 'Удаление автора',
        tags: ['author'],
        parameters: [
            new OA\Parameter(
                ref: "#/components/parameters/author",
            )
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_NO_CONTENT,
                description: 'No Content'
            ),
            new OA\Response(
                ref: "#/components/responses/not_found",
                response: Response::HTTP_NOT_FOUND,
            ),
            new OA\Response(
                ref: "#/components/responses/unauthenticated",
                response: Response::HTTP_UNAUTHORIZED,
            )
        ]
    )

]
class DeleteAuthorDocumentation
{

}
