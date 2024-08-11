<?php

namespace App\Books\Requests;

use App\Http\Requests\DefaultRequest;

/**
 * Класс CreateBookRequest отвечает за валидацию и обработку
 * входящих данных при создании новой записи книги.
 *
 * @property string $title
 * @property int $author_id
 * @package App\Books\Requests
 * @extends DefaultRequest
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 11.08.2024 18:06
 */
class CreateBookRequest extends DefaultRequest
{
    /**
     * Правила валидации
     *
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:150',
            'author_id' => 'required|numeric|min:0',
        ];
    }

}
