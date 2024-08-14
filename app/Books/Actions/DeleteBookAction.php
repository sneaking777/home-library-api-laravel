<?php

namespace App\Books\Actions;

use App\Models\Book;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Symfony\Component\HttpFoundation\Response;

/**
 * Класс DeleteBookAction
 *
 * Класс предназначен для удаления книги в приложении.
 *
 * @package App\Books\Actions
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 14.08.2024 16:20
 */
class DeleteBookAction
{

    /**
     * Призыватель
     *
     * @param Book $book
     * @return ResponseFactory|Application|\Illuminate\Http\Response
     */
    public function __invoke(Book $book): ResponseFactory|Application|\Illuminate\Http\Response
    {
        $book->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
