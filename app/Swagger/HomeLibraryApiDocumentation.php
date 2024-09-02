<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(version: "0.9.0", title: "API Системы Управления Домашней Библиотекой")]
#[OA\Server(
    url: 'http://localhost/api/v1',
    description: 'Локальный сервер.',
)]
#[OA\Tag(
    name: 'book',
    description: 'Все эндпоинты API, относящиеся к книгам'
)]
#[OA\Tag(
    name: 'auth',
    description: 'Все эндпоинты API, относящиеся к аутентификации пользователя'
)]
#[OA\Tag(
    name: 'author',
    description: 'Все эндпоинты API, относящиеся к авторам'
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
        ),
        'login_validation_error' => new OA\Response(
            response: 'login_validation_error',
            description: 'Unprocessable Content',
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
                            'errors' => new OA\Property(
                                property: 'errors',
                                description: 'Массив ошибок',
                                type: 'string',
                            ),
                        ]
                    ),
                    example: [
                        "message" => 'Предоставленные учетные данные неверны.',
                        "errors" => [
                            [
                                "Предоставленные учетные данные неверны."
                            ]
                        ]
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
        ),
        'author' => new OA\Parameter(
            name: 'author',
            description: 'Идентификатор автора',
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
