<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(version: "0.1.4", title: "API Системы Управления Домашней Библиотекой")]
#[OA\Server(
    url: 'http://localhost/api/v1',
    description: 'Локальный сервер.',
)]
#[OA\Tag(
    name: 'book',
    description: 'Все эндпоинты API, относящиеся к книгам'
)]
#[OA\Components(
    responses: [
        'not_found' => new OA\Response(
            response: 'not_found',
            description: 'Not Found',
            content: [
                "application/json" => new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            'message' => new OA\Property(
                                property: 'message',
                                description: 'Сообщение',
                                type: 'string',
                            ),
                        ]
                    ),
                    example: [
                        "message" => 'Запрашиваемый ресурс не найден',
                    ],
                )
            ]
        ),
        'book_validation_errors' => new OA\Response(
            response: 'book_validation_errors',
            description: 'Unprocessable Content',
            content: [
                'application/json' => new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            'message' => new OA\Property(
                                property: 'message',
                                description: 'Сообщение об ошибках.',
                                type: 'string',
                            ),
                            'errors' => new OA\Property(
                                property: 'errors',
                                description: 'Перечисление ощибок валидации',
                                properties: [

                                ],
                                type: 'object'
                            ),
                        ],
                        type: 'object'
                    ),
                    example: [
                        'message' => "The title field is required. (and 1 more error)",
                        'errors' => [
                            'title' => [
                                "The title field is required."
                            ],
                            "author_id" => [
                                "The author_id field is required."
                            ],
                        ]
                    ]
                ),
            ],

        ),
        'unauthenticated' => new OA\Response(
            response: 'unauthenticated',
            description: 'Unauthorized',
            content: [
                "application/json" => new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            'message' => new OA\Property(
                                property: 'message',
                                description: 'Сообщение',
                                type: 'string',
                            ),
                        ]
                    ),
                    example: [
                        "message" => 'Unauthenticated.',
                    ],
                )
            ]
        )

    ],
    parameters: [
        'book' => new OA\Parameter(
            name: 'book',
            description: 'Идентификатор книги',
            in: 'path',
            required: true,
            schema: new OA\Schema(
                type: 'integer'
            )
        )
    ]
)]
class HomeLibraryApiDocumentation
{

}
