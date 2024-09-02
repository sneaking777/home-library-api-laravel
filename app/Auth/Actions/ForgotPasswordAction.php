<?php

namespace App\Auth\Actions;

use App\Auth\Requests\ForgotPasswordRequest;
use App\Models\User;
use App\Responders\BaseResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpFoundation\Response;

/**
 * Класс ForgotPasswordDocumentation
 *
 * Этот класс отвечает за обработку действий восстановления пароля пользователей
 *
 * @package App\Auth
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 28.08.2024 8:08
 */
readonly class ForgotPasswordAction
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
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     */
    public function __invoke(ForgotPasswordRequest $request): JsonResponse
    {
        $user = User::query()->where('email', $request->email)->first();
        if (!$user) {

            return $this->responder->respond([
                'message' => __('messages.not_found.user')
            ], Response::HTTP_NOT_FOUND);
        }
        $token = Password::getRepository()->create($user);
        // TODO в будущем следует написать Unit тест для $user->sendPasswordResetNotification($token)
        $user->sendPasswordResetNotification($token);

        return $this->responder->respond([
            'message' => __('messages.password_reset')],
            Response::HTTP_OK);
    }
}
