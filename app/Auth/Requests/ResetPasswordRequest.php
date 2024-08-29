<?php

namespace App\Auth\Requests;

use App\Http\Requests\DefaultRequest;

/**
 * Класс ResetPasswordRequest
 *
 * Класс отвечает за валидацию и обработку входящих данных при сбросе старого пароля и добавления нового пользователя
 *
 * @extends DefaultRequest
 * @package App\Auth\Requests
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 29.08.2024 10:56
 */
class ResetPasswordRequest extends DefaultRequest
{
    /**
     * Правила валидации
     *
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'token' => 'required'
        ];
    }

}
