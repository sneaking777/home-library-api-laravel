<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Модель таблицы 'books'
 *
 * @property string $title
 * @property int $author_id
 * @package App\Models
 * @extends Model
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 09.08.2024 16:36
 */
class Book extends Model
{
    use HasFactory;

    /**
     * @var string наименование таблицы
     */
    protected $table = 'books';

    /**
     * Даний метод определяет "обратную" связь (inverse relation) от модели Book
     * к модели Author в контексте связи "один ко многим" (one-to-many)
     *
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * @inheritdoc
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'author_id'
    ];
}
