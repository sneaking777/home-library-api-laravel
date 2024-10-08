<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Auth\Domain\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use SensitiveParameter;

/**
 * Класс User
 *
 * Модель таблицы 'users'
 *
 * @property string $password
 * @property string $name
 * @property string $email
 * @method createToken(string $tokenName)
 * @method withAccessToken($accessToken)
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

    /**
     * Отправляет уведомление о сбросе пароля.
     *
     * Этот метод генерирует уведомление о сбросе пароля и отправляет его пользователю.
     * Токен для сброса пароля, передаваемый в этот метод, является чувствительным параметром
     * и должен быть обработан соответствующим образом.
     *
     * @param string $token токен для сброса пароля.
     * @return void
     */
    public function sendPasswordResetNotification(#[SensitiveParameter] $token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
