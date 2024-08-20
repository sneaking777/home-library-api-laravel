<?php

namespace App\Swagger\Books;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[
    OA\Post(
        path: '/book',
        description: 'Запрос на создание книги.',
        requestBody: new OA\RequestBody(
            description: 'JSON объект для создания новой книги',
            content: [
                "application/json" => new OA\MediaType(
                    schema: new OA\Schema(
                        required: ["title", "author_id"],
                        properties: [
                            'title' => new OA\Property(
                                description: "Название книги",
                                type: 'string'
                            ),
                            'author_id' => new OA\Property(
                                description: 'Идентификатор автора книги',
                                type: 'integer',
                            )
                        ],
                        type: "object"
                    )
                )
            ]
        ),
        tags: ['book'],
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: 'Created',
                content: [
                    "application/json" => new OA\MediaType(
                        mediaType: "application/json",
                        schema: new OA\Schema(
                            properties: [
                                'title' => new OA\Property(
                                    property: 'title',
                                    description: 'Название книги',
                                    type: 'string',
                                ),

                            ]
                        )
                    )
                ]

            )
        ]
    )
]
class CreateBookAction
{

}
