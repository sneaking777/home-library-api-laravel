<?php

namespace App\Enums;

/**
 * Перечисление каналов уведомлений
 *
 * @package App\Enums
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 28.08.2024 8:54
 */
enum NotificationChannelsEnum: string
{
    case MAIL = 'mail';
    case DATABASE = 'database';
}
