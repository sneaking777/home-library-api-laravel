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
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        description: 'Тело запроса.',
                        required: ['title', 'author_id'],
                        properties: [
                            'title' => new OA\Property(
                                property: 'title',
                                description: 'Название книги',
                                type: 'string'
                            ),
                            'author_id' => new OA\Property(
                                property: 'author_id',
                                description: 'ID автора книги.',
                                type: 'integer'
                            )
                        ],
                        type: 'object',
                        maxLength: 150
                    ),
                    example: [
                        "title" => "Кулинарные рецепты",
                        "author_id" => 81
                    ]
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
                                'message' => new OA\Property(
                                    property: 'message',
                                    description: 'Сообщение об успешном создании книги.',
                                    type: 'string',
                                ),
                                'book' => new OA\Property(
                                    property: 'book',
                                    description: 'Информация о книге.',
                                    properties: [
                                        'id' => new OA\Property(
                                            property: 'id',
                                            description: 'ID книги.',
                                            type: 'integer',
                                        ),
                                        'author' => new OA\Property(
                                            property: 'author',
                                            description: 'Информация о авторе книги.',
                                            properties: [
                                                'id' => new OA\Property(
                                                    property: 'id',
                                                    description: 'ID автора.',
                                                    type: 'integer',
                                                ),
                                                'surname' => new OA\Property(
                                                    property: 'surname',
                                                    description: 'Фамилия автора',
                                                    type: 'string',
                                                ),
                                                'name' => new OA\Property(
                                                    property: 'name',
                                                    description: 'Имя автора',
                                                    type: 'string',
                                                ),
                                                'patronymic' => new OA\Property(
                                                    property: 'patronymic',
                                                    description: 'Отчество автора',
                                                    type: 'string',
                                                ),
                                            ],
                                            type: "object"
                                        )
                                    ],
                                    type: 'object'
                                )
                            ]
                        ),
                        example: [
                            'message' => 'Книга успешно создана.',
                            "book" => [
                                "id" => 1,
                                "title" => "Как войти в IT.",
                                "author" => [
                                    "id" => 1,
                                    "surname" => "Иванов",
                                    "name" => "Иван",
                                    "patronymic" => "Иванович",
                                ],
                            ],
                        ]
                    )
                ]
            ),
            new OA\Response(
                response: Response::HTTP_UNPROCESSABLE_ENTITY,
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
            )
        ]
    )
]
class CreateBookAction
{

}
