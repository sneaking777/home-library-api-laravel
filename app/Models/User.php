<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Класс User
 *
 * Модель таблицы 'users'
 *
 * @property string $password
 * @property string $name
 * @property string $email
 * @extends Authenticatable
 * @package App\Models
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 24.08.2024 19:27
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * @var string[]
     * Список атрибутов, которые могут быть массово присвоены.
     * Любые другие атрибуты, которые не указаны в этом списке, не могут быть массово присвоены.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * @var string[]
     * Список атрибутов, которые должны быть скрыты для массивов.
     * Когда модель преобразуется в массив или JSON, атрибуты,
     * указанные в этом списке, будут скрыты от результата.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Получить список атрибутов, которые должны быть приведены к определенным типам данных
     * при извлечении из базы данных.
     *
     * @return string[]
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
