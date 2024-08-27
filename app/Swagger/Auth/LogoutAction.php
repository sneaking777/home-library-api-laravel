<?php

namespace App\Swagger\Auth;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[OA\Delete(
    path: "/auth/logout",
    description: 'Запрос на выход пользователя из системы.',
    summary: 'Выход',
    tags: ['auth'],
    responses: [
        new OA\Response(
            response: Response::HTTP_OK,
            description: 'OK',
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
                        "message" => 'Выход из системы успешно выполнен',
                    ],
                )
            ]
        ),
        new OA\Response(
            ref: "#/components/responses/unauthenticated",
            response: Response::HTTP_UNAUTHORIZED,
        ),
    ]
)]
class LogoutAction
{

}
