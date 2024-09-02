<?php

namespace App\Authors\Actions;

use App\Http\Resources\AuthorResource;
use App\Models\Author;

/**
 * Класс ShowAuthorDetailAction
 *
 * Класс предназначен для обработки запросов на вывод детальной о авторе
 *
 * @package App\Authors\Actions
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 02.09.2024 9:20
 */
readonly class ShowAuthorDetailAction
{
    /**
     * Призыватель
     *
     * @param Author $author
     * @return AuthorResource
     */
    public function __invoke(Author $author): AuthorResource
    {
        return new AuthorResource($author);
    }

}
