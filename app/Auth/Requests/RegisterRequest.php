<?php

namespace App\Auth\Requests;

use App\Http\Requests\DefaultRequest;

/**
 * Класс RegisterRequest
 *
 * Класс отвечает за валидацию и обработку входящих данных при регистрации пользователя
 *
 * @property string $name
 * @property string $email
 * @property string $password
 * @extends DefaultRequest
 * @package App\Auth\Requests
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 26.08.2024 8:38
 */
class RegisterRequest extends DefaultRequest
{
    /**
     * Правила валидации
     *
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ];
    }

}
