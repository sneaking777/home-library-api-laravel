<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Модель таблицы 'authors'
 *
 *
 * @extends Model
 * @package App\Models
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 11.08.2024 18:27
 * @method static findOrFail(int $author_id)
 */
class Author extends Model
{
    use HasFactory;

    /**
     * @var string наименование таблицы
     */
    protected $table = 'authors';

}
