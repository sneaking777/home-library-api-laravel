<?php

namespace App\Swagger\Auth;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[OA\Post(
    path: '/auth/reset-password',
    description: 'Запрос на сброс и получение нового пароля',
    summary: 'Сброс пароля',
    security: null,
    requestBody: new OA\RequestBody(
        description: 'JSON объект учетных данных пользователя для сброса пароля',
        content: [
            'application/json' => new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    description: 'Тело запроса.',
                    required: ['email', 'password', 'password_confirmation', 'token'],
                    properties: [
                        'token' => new OA\Property(
                            property: 'token',
                            description: 'token сброса пароля',
                            type: 'string'
                        ),
                        'email' => new OA\Property(
                            property: 'email',
                            description: 'email',
                            type: 'string'
                        ),
                        'password' => new OA\Property(
                            property: 'password',
                            description: 'Новый пароль.',
                            type: 'string',
                            minLength: 6
                        ),
                        'password_confirmation' => new OA\Property(
                            property: 'password_confirmation',
                            description: 'Подтверждение нового пароля.',
                            type: 'string',
                            minLength: 6
                        )
                    ],
                    type: 'object',
                ),
                example: [
                    'token' => "bb0bdfe208d0bc60f567fcb168fe561dca5ac9581bb4fd8e97e6037b2276194d",
                    "email" => "john.doe@example.com",
                    'password' => '123456',
                    'password_confirmation' => '123456'
                ]
            )
        ],
    ),
    tags: ['auth'],
    responses: [
        new OA\Response(
            response: Response::HTTP_OK,
            description: 'OK',
            content: [
                'application/json' => new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            'message' => new OA\Property(
                                property: 'message',
                                description: 'Сообщение.',
                                type: 'string'
                            ),
                        ]
                    ),
                    example: [
                        'message' => 'Пароль успешно сброшен.',
                    ]
                )
            ]
        ),
        new OA\Response(
            response: Response::HTTP_UNPROCESSABLE_ENTITY,
            description: 'Unprocessable Entity',
            content: [
                'application/json' => new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            'error' => new OA\Property(
                                property: 'error',
                                description: 'Сообщение об ошибке',
                                type: 'string'
                            ),
                        ]
                    ),
                    example: [
                        'error' => 'Не удалось сбросить пароль.',
                    ]
                )
            ]
        ),
    ]

)]
class ResetPasswordDocumentation
{

}
