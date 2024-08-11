<?php

namespace App\Authors\Actions;

use App\Responders\BaseResponder;

readonly class CreateAuthorAction
{
    private BaseResponder $responder;

    public function __construct(BaseResponder $responder)
    {
        $this->responder = $responder;
    }

    public function __invoke(CreateBookRequest $request): JsonResponse
    {
    }
}
