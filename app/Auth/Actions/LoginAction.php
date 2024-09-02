<?php

namespace App\Auth\Actions;

use App\Auth\Requests\LoginRequest;
use App\Responders\BaseResponder;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Класс LoginDocumentation
 *
 * Этот класс отвечает за обработку действий авторизации пользователя в системе
 *
 * @package App\Auth
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 25.08.2024 1:01
 */
readonly class LoginAction
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

    /**
     * Призыватель
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {

        $tokenName = $request->ip() . '-' . now();
        $result['data'] = [
            'access_token' => $request->user->createToken($tokenName)->plainTextToken,
            'token_type' => 'Bearer'
        ];
        $result['status'] = Response::HTTP_OK;

        return $this->responder->respond($result['data'], $result['status']);
    }
}
