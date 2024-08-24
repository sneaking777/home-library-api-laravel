<?php

namespace App\Auth\Requests;

use App\Http\Requests\DefaultRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Класс LoginRequest
 *
 * Класс отвечает за валидацию и обработку входящих данных
 * при входе пользователя в систему
 *
 * @property string $email
 * @property string $password
 * @extends DefaultRequest
 * @package App\Auth\Requests
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 24.08.2024 22:01
 */
class LoginRequest extends DefaultRequest
{
    /**
     * @var User модель пользователя
     */
    public User $user;

    /**
     * Правила валидации
     *
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    /**
     * Проверяет валидацию запроса и выполняет дополнительные
     * проверки для аутентификации пользователя.
     *
     * @return void
     * @throws ValidationException
     */
    public function validateResolved(): void
    {
        parent::validateResolved();

        $user = $this->getUserByCredentials($this->only('email', 'password'));

        if (!$user || !Hash::check($this->password, $user->password)) {
            $result['data'] = [
                'error' => __('exceptions.not_authorized')
            ];

            throw ValidationException::withMessages([$result['data']]);
        }

        $this->user = $user;
    }

    /**
     * Извлекает учетные данные пользователя на основе переданных данных.
     *
     * @param array $credentials
     * @return User|null
     */
    private function getUserByCredentials(array $credentials): ?User
    {
        return User::query()->where('email', $credentials['email'])->first();
    }
}
