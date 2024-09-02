<?php

namespace App\Swagger\Auth;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[OA\Post(
    path: '/auth/register',
    description: 'Запрос на регистрацию пользователя.',
    summary: 'Регистрация',
    security: null,
    requestBody: new OA\RequestBody(
        description: 'JSON объект данных пользователя',
        content: [
            'application/json' => new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    description: 'Тело запроса.',
                    required: ['email', 'password', 'password_confirmation', 'name'],
                    properties: [
                        'name' => new OA\Property(
                            property: 'name',
                            description: 'Имя пользователя',
                            type: 'string',
                            maxLength: 255
                        ),
                        'email' => new OA\Property(
                            property: 'email',
                            description: 'email',
                            type: 'string',
                            maxLength: 255
                        ),
                        'password' => new OA\Property(
                            property: 'password',
                            description: 'Пароль.',
                            type: 'string',
                            minLength: 6
                        ),
                        'password_confirmation' => new OA\Property(
                            property: 'password_confirmation',
                            description: 'Подтверждение пароля.',
                            type: 'string',
                            minLength: 6
                        ),
                    ],
                    type: 'object',
                    example: [
                        "email" => "john.doe@example.com",
                        "password" => 'password',
                        "password_confirmation" => 'password',
                        "name" => 'John Doe',
                    ]
                )
            )
        ]
    ),
    tags: ['auth'],
    responses: [
        new OA\Response(
            response: Response::HTTP_CREATED,
            description: 'Created',
            content: [
                'application/json' => new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            'name' => new OA\Property(
                                property: 'name',
                                description: 'Имя пользователя.',
                                type: 'string',
                                maxLength: 255
                            ),
                            'email' => new OA\Property(
                                property: 'email',
                                description: 'email.',
                                type: 'string',
                                maxLength: 255

                            ),
                            'password' => new OA\Property(
                                property: 'password',
                                description: 'Пароль.',
                                type: 'string',
                                minLength: 6
                            ),
                            'password_confirmation' => new OA\Property(
                                property: 'password_confirmation',
                                description: 'Подтверждение пароля.',
                                type: 'string',
                                minLength: 6
                            )
                        ]
                    ),
                    example: [
                        'name' => 'John Doe',
                        'email' => 'john.doe@example.com',
                        'id' => 1
                    ]
                )
            ]
        ),
        new OA\Response(
            response: Response::HTTP_UNPROCESSABLE_ENTITY,
            description: 'Unprocessable Entity',
            content: [
                'name_required' => new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            'message' => new OA\Property(
                                property: 'message',
                                description: 'Сообщение об ошибке'
                            ),
                            'errors' => new OA\Property(
                                property: 'errors',
                                description: 'Массив ошибок',
                                properties: [
                                    'name' => new OA\Property(
                                        property: 'name',
                                        type: 'array',
                                        items: new OA\Items(
                                            properties: [
                                                'name' => new OA\Property(
                                                    property: 'name',
                                                    description: 'Невалидное имя пользователя',
                                                    type: 'string'
                                                )
                                            ]
                                        )
                                    )
                                ],
                                type: 'object'
                            )
                        ],
                        example: [
                            'message' => "The name field is required.",
                            'errors' => [
                                'name' => [
                                    "The name field is required."
                                ]
                            ]
                        ],
                    )
                ),
            ]

        )
    ]
)]
class RegisterDocumentation
{

}
