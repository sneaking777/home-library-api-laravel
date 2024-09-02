<?php

namespace App\Auth\Actions;

use App\Responders\BaseResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Класс LogoutDocumentation
 *
 * Этот класс отвечает за обработку действий выхода пользователя из системы
 *
 * @package App\Auth
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 26.08.2024 12:04
 */
readonly class LogoutAction
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
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        $result['data'] = ['message' => __('messages.success.logout')];
        $result['status'] = Response::HTTP_OK;

        return $this->responder->respond($result['data'], $result['status']);
    }
}
