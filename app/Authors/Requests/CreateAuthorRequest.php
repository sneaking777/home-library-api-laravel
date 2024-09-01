<?php

namespace App\Authors\Requests;

use App\Http\Requests\DefaultRequest;

/**
 * Класс CreateAuthorRequest отвечает за валидацию и обработку
 * входящих данных при создании новой записи о авторе.
 *
 * @property string $surname
 * @property string $name
 * @property string $patronymic
 * @extends DefaultRequest
 * @package App\Authors\Requests
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 01.09.2024 9:42
 */
class CreateAuthorRequest extends DefaultRequest
{
    /**
     * Правила валидации
     *
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'surname' => 'required|string|max:100',
            'name' => 'required|string|max:100',
            'patronymic' => 'required|string|max:100',
        ];
    }
}
