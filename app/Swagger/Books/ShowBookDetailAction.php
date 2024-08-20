<?php

namespace App\Swagger\Books;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[
    OA\Get(
        path: '/book/{book}',
        description: 'Запрос на вывод детальной информации о конкретной книге.',
        summary: 'Введите идентификатор книги',
        tags: ['book'],
        parameters: [
            new OA\Parameter(
                ref: "#/components/parameters/book",
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
                                    description: 'Данные о книге',
                                    properties: [
                                        'id' => new OA\Property(
                                            property: 'id',
                                            description: 'ID книги',
                                            type: 'integer',
                                        ),
                                        'title' => new OA\Property(
                                            property: 'title',
                                            description: 'Название книги',
                                            type: 'string',
                                        ),
                                        'author' => new OA\Property(
                                            property: 'author',
                                            description: 'Автор книги.',
                                            properties: [
                                                'id' => new OA\Property(
                                                    property: 'id',
                                                    description: 'ID автора',
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
                                            type: 'object'
                                        ),
                                    ],
                                    type: 'object'
                                ),
                            ],
                            type: "object"
                        ),
                        example: [
                            "data" => [
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
                    ),
                ]),
            new OA\Response(
                ref: "#/components/responses/book_not_found",
                response: Response::HTTP_NOT_FOUND,
            )
        ]

    )]
class ShowBookDetailAction
{

}
