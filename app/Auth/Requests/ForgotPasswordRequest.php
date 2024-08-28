<?php

namespace App\Auth\Requests;

use App\Http\Requests\DefaultRequest;

/**
 * Класс ForgotPasswordRequest
 *
 * Класс отвечает за валидацию и обработку входящих данных
 * при запросе на восстановление пароля пользователем
 *
 * @property string $email
 * @extends DefaultRequest
 * @package App\Auth\Requests
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 27.08.2024 13:20
 */
class ForgotPasswordRequest extends DefaultRequest
{
    /**
     * Правила валидации
     *
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|email',
        ];
    }

}
