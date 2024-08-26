<?php

namespace App\Swagger\Auth;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[OA\Post(
    path: '/auth/login',
    description: 'Запрос на идентификацию и авторизацию пользователя.',
    summary: 'Авторизация',
    security: null,
    requestBody: new OA\RequestBody(
        description: 'JSON объект учетных данных пользователя',
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
                        'password' => new OA\Property(
                            property: 'password',
                            description: 'Пароль.',
                            type: 'string'
                        )
                    ],
                    type: 'object',
                ),
                example: [
                    "email" => "john.doe@example.com",
                    "password" => 'password'
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
                            'access_token' => new OA\Property(
                                property: 'access_token',
                                description: 'Токен доступа.',
                                type: 'string'
                            ),
                            'token_type' => new OA\Property(
                                property: 'token_type',
                                description: 'Тип токена.',
                                type: 'string'
                            ),
                        ]
                    ),
                    example: [
                        'access_token' => '2|ioWWhr1rvgJuMGGzpdGD1jagfVaC5JSQV0uIQll480242318',
                        'token_type' => 'Bearer',
                    ]
                )
            ]

        ),
        new OA\Response(
            ref: "#/components/responses/login_validation_error",
            response: Response::HTTP_UNPROCESSABLE_ENTITY,
        ),
    ]

)
]
class LoginAction
{

}
