<?php

namespace App\Auth\Actions;

use App\Responders\BaseResponder;

readonly class ResetPasswordAction
{
    /**
     * @var BaseResponder
     */
    private BaseResponder $responder;

    /**
     * Конструктор
     *
     * @param BaseResponder $responder
     */
    public function __construct(BaseResponder $responder)
    {
        $this->responder = $responder;
    }

    public function __invoke()
    {
        dd('fdg');
    }
}
