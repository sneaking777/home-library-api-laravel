<?php

namespace App\Auth\Actions;

use App\Auth\Requests\ResetPasswordRequest;
use App\Responders\BaseResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpFoundation\Response;

/**
 * Класс ResetPasswordDocumentation
 *
 * Класс отвечает за валидацию и обработку входящих данных
 * при запросе пароля пользователем
 *
 * @package App\Auth\Actions
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 29.08.2024 11:27
 */
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

    /**
     * Призыватель
     *
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function __invoke(ResetPasswordRequest $request): JsonResponse
    {
        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
        $response = Password::reset($credentials, function ($user, $password) {
            $user->password = $password;
            $user->save();
        });
        if ($response === Password::PASSWORD_RESET) {

            return $this->responder->respond([
                'message' => __('messages.new_password')],
                Response::HTTP_OK);
        }

        return $this->responder->respond(
            ['error' => __('errors.password_reset')],
            Response::HTTP_UNPROCESSABLE_ENTITY);

    }
}
