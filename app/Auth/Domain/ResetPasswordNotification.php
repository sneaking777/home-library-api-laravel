<?php

namespace App\Auth\Domain;

use App\Enums\NotificationChannelsEnum;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Класс ResetPasswordNotification
 *
 * Уведомление о сбросе пароля
 *
 * @extends Notification
 * @package App\Auth\Domain
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 28.08.2024 9:03
 */
class ResetPasswordNotification extends Notification
{
    /**
     * Токен для ссылки сброса пароля
     *
     * @var string
     */
    public string $token;

    /**
     * Конструктор
     *
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Метод отправляет уведомление на почту пользователя
     *
     * @noinspection PhpUnused
     * @param User $notifiable
     * @return MailMessage
     */
    public function toMail(User $notifiable): MailMessage
    {
        $url = url()->to(
            route('auth.reset', [], false) . '?' . http_build_query([
                'token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()]));
        return (new MailMessage)
            ->subject(__('messages.mail.password_reset.subject'))
            ->line(__('messages.mail.password_reset.message'))
            ->action(
                __('messages.mail.password_reset.action'),
                $url
            )
            ->line(__('messages.mail.password_reset.warning'));
    }

    /**
     * Метод определяет через какие каналы следует отправлять уведомление
     *
     * @return array
     */
    public function via(): array
    {
        return [NotificationChannelsEnum::MAIL->value];
    }
}
