<?php

namespace App\Books\Requests;

use App\Http\Requests\DefaultRequest;

/**
 * @property string $title
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
        ];
    }

}
