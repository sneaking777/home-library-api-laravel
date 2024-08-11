<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Модель таблицы 'books'
 *
 * @property string $title
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
}
