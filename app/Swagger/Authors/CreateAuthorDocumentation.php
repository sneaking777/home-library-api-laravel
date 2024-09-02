<?php

namespace App\Swagger\Authors;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[
    OA\Post(
        path: '/author',
        description: 'Запрос на создание автора.',
        summary: 'Создание автора',
        requestBody: new OA\RequestBody(
            description: 'JSON объект для создания нового автора',
            content: [
                "application/json" => new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        description: 'Тело запроса.',
                        required: ['surname', 'name'],
                        properties: [
                            'surname' => new OA\Property(
                                property: 'surname',
                                description: 'Фамилия',
                                type: 'string',
                                maxLength: 100
                            ),
                            'name' => new OA\Property(
                                property: 'name',
                                description: 'Имя',
                                type: 'string',
                                maxLength: 100
                            ),
                            'patronymic' => new OA\Property(
                                property: 'patronymic',
                                description: 'Отчество',
                                type: 'string',
                                maxLength: 100
                            ),
                        ],
                        type: 'object',
                    ),
                    example: [
                        "surname" => "Иванов",
                        "name" => "Иван",
                        "patronymic" => "Иванович",
                    ]
                )
            ]
        ),
        tags: ['author'],
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
                                    description: 'Автор успешно создан.',
                                    type: 'string',
                                ),
                                'author' => new OA\Property(
                                    property: 'author',
                                    description: 'Информация о авторе.',
                                    properties: [
                                        'id' => new OA\Property(
                                            property: 'id',
                                            description: 'Идентификатор автора.',
                                            type: 'integer'
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
                                ),
                            ],
                            type: 'object'
                        ),
                        example: [
                            'message' => 'Автор успешно создан.',
                            "author" => [
                                "id" => 1,
                                "surname" => "Иванов",
                                "name" => "Иван",
                                "patronymic" => "Иванович",
                            ],
                        ]
                    ),
                ]
            ),
            new OA\Response(
                ref: "#/components/responses/unauthenticated",
                response: Response::HTTP_UNAUTHORIZED,
            )
        ]
    )
]
class CreateAuthorDocumentation
{

}
