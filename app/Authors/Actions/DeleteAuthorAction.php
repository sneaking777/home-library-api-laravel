<?php

namespace App\Authors\Actions;

use App\Models\Author;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Symfony\Component\HttpFoundation\Response;

/**
 * Класс DeleteAuthorAction
 *
 * Класс предназначен для удаления авторов книг в приложении.
 *
 * @package App\Authors\Actions
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 03.09.2024 12:57
 */
class DeleteAuthorAction
{
    /**
     * Призыватель
     *
     * @param Author $author
     * @return Application|\Illuminate\Http\Response|ResponseFactory
     */
    public function __invoke(Author $author): Application|\Illuminate\Http\Response|ResponseFactory
    {
        $author->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
