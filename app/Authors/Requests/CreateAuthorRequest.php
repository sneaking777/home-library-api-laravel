<?php

namespace App\Authors\Requests;

use App\Http\Requests\DefaultRequest;

class CreateAuthorRequest extends DefaultRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:150',
            'author_id' => 'required|numeric|min:0',
        ];
    }
}
