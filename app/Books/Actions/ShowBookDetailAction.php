<?php

namespace App\Books\Actions;

use App\Http\Resources\BookResource;
use App\Models\Book;

/**
 * Класс ShowBookDetailAction
 *
 * Класс предназначен для обработки запросов на вывод детальной информации о конкретной книге
 *
 * @package App\Books\Actions
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 12.08.2024 17:03
 */
readonly class ShowBookDetailAction
{

    /**
     * Призыватель
     *
     * @param Book $book
     * @return BookResource
     */
    public function __invoke(Book $book): BookResource
    {
        return new BookResource($book->load('author'));
    }
}
