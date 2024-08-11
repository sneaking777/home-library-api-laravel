<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Основной обработчик HTTP-запросов
 *
 * @package App\Http\Requests
 * @extends FormRequest
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 20.06.2024 5:48
 */
class DefaultRequest extends FormRequest
{
    /**
     * Определить, разрешено ли пользователю совершить этот запрос.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
