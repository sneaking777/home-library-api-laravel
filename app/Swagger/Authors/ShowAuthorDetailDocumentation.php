<?php

namespace App\Swagger\Authors;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[
    OA\Get(
        path: "/author/{author}",
        description: 'Запрос на вывод детальной информации о авторе',
        summary: 'Информация о авторе',
        tags: ['author'],
        parameters: [
            new OA\Parameter(
                ref: "#/components/parameters/author",
            )
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'OK',
                content: [
                    "application/json" => new OA\MediaType(
                        mediaType: "application/json",
                        schema: new OA\Schema(
                            properties: [
                                'data' => new OA\Property(
                                    property: 'data',
                                    description: 'Данные об авторе.',
                                    properties: [
                                        'id' => new OA\Property(
                                            property: 'id',
                                            description: 'ID автора',
                                            type: 'integer',
                                        ),
                                        'surname' => new OA\Property(
                                            property: 'surname',
                                            description: 'Фамилия.',
                                            type: 'string',
                                        ),
                                        'name' => new OA\Property(
                                            property: 'name',
                                            description: 'Имя.',
                                            type: 'string',
                                        ),
                                        'patronymic' => new OA\Property(
                                            property: 'patronymic',
                                            description: 'Отчество.',
                                            type: 'string',
                                        ),
                                    ],
                                    type: 'object'
                                )
                            ],
                            type: 'object'
                        ),
                        example: [
                            "data" => [
                                "id" => 1,
                                "surname" => "Иванов",
                                "name" => "Иван",
                                "patronymic" => "Иванович",
                            ],
                        ]
                    )
                ]
            ),
            new OA\Response(
                ref: "#/components/responses/not_found",
                response: Response::HTTP_NOT_FOUND,
            ),
            new OA\Response(
                ref: "#/components/responses/unauthenticated",
                response: Response::HTTP_UNAUTHORIZED,
            )
        ]
    )
]
class ShowAuthorDetailDocumentation
{

}
