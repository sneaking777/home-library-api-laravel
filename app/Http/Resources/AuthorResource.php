<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 *
 * Класс AuthorResource
 *
 * Класс API ресурса, который используется для преобразования экземпляров модели Eloquent Author в массивы данных API
 *
 * @property int $id
 * @property string $surname
 * @property string $name
 * @property string $patronymic
 * @extends JsonResource
 * @package App\Http\Resources
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 12.08.2024 17:08
 */
class AuthorResource extends JsonResource
{
    /**
     * @inheritdoc
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'surname' => $this->surname,
            'name' => $this->name,
            'patronymic' => $this->patronymic,
        ];
    }
}
