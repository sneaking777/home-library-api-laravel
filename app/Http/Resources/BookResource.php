<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Класс BookResource
 *
 * Класс API ресурса, который используется для преобразования экземпляров модели Eloquent Book в массивы данных API
 *
 * @property int $id
 * @property string $title
 * @extends JsonResource
 * @package App\Http\Resources
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 12.08.2024 17:06
 */
class BookResource extends JsonResource
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
            'title' => $this->title,
            'author' => new AuthorResource($this->whenLoaded('author')),
        ];
    }
}
