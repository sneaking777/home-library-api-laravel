<?php

namespace App\Swagger\Auth;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[OA\Post(
    path: '/auth/forgot-password',
    description: 'Запрос на сброс пароля',
    summary: 'Сброс пароля',
    security: null,
    requestBody: new OA\RequestBody(
        description: 'JSON объект учетных данных пользователя для сброса пароля',
        content: [
            'application/json' => new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    description: 'Тело запроса.',
                    required: ['email', 'password'],
                    properties: [
                        'email' => new OA\Property(
                            property: 'email',
                            description: 'email',
                            type: 'string'
                        ),
                    ],
                    type: 'object',
                ),
                example: [
                    "email" => "john.doe@example.com",
                ]
            )
        ]
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
                        'message' => 'Мы выслали вам ссылку для сброса пароля по электронной почте!',
                    ]
                )
            ]
        ),
        new OA\Response(
            response: Response::HTTP_NOT_FOUND,
            description: 'Not Found',
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
                        'message' => 'Пользователь не найден.',
                    ]
                )
            ]
        ),
    ]

)]
class ForgotPasswordAction
{

}
