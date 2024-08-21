<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(version: "0.1.2", title: "API Системы Управления Домашней Библиотекой")]
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
        'book_not_found' => new OA\Response(
            response: 'book_not_found',
            description: 'Книга не найдена.',
            content: [
                "application/json" => new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            'message' => new OA\Property(
                                property: 'message',
                                description: 'Сообщение',
                                type: 'string',
                            )
                        ]
                    ),
                    example: [
                        'message' => 'Запрашиваемая книга не найдена.'
                    ]
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
