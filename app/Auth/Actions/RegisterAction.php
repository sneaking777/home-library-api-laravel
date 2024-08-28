<?php

namespace App\Auth\Actions;

use App\Auth\Requests\RegisterRequest;
use App\Models\User;
use App\Responders\BaseResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

/**
 * Класс RegisterAction
 *
 * Этот класс отвечает за обработку действий регистрации пользователя в системе
 *
 * @package App\Auth
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 26.08.2024 8:53
 */
readonly class RegisterAction
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
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();
        $user->makeHidden(['updated_at', 'created_at']);
        $result['data'] = $user->toArray();
        $result['status'] = Response::HTTP_CREATED;

        return $this->responder->respond($result['data'], $result['status']);
    }
}
