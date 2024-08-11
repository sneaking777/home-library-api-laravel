<?php

namespace App\Books\Actions;

use App\Books\Requests\CreateBookRequest;
use App\Books\Responders\BaseResponder;
use App\Models\Book;

readonly class CreateBookAction
{
    private BaseResponder $responder;

    public function __construct(BaseResponder $responder)
    {
        $this->responder = $responder;
    }

    public function __invoke(CreateBookRequest $request)
    {
        $book = new Book;
        $book->title = $request->title;
        $book->save();
        $result['data'] = $book->toArray();
        $result['status'] = 201;

        return $this->responder->respond($result['data'], $result['status']);
    }
}
