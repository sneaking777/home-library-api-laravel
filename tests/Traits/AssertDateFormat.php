<?php

namespace Tests\Traits;


use Exception;
use Illuminate\Support\Carbon;

/**
 * Трейт AssertDateFormat.
 *
 * Этот трейт предоставляет дополнительное утверждение для PHPUnit тестов.
 * Оно позволяет проверить, что указанная дата соответствует заданному формату.
 *
 * Пример использования:
 * $this->assertDateFormat('2022-12-31', 'Y-m-d');
 *
 * @package Tests\Traits
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 10.08.2024 14:53
 */
trait AssertDateFormat
{
    /**
     * Проверяет, что дата в строке соответствует заданному формату.
     *
     * @param string $date Строка с датой
     * @param string $format Формат даты, который надо проверить
     * @return void
     */
    public function assertDateFormat(string $date, string $format): void
    {
        try {
            Carbon::createFromFormat($format, $date);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->assertTrue(
                false,
                "Не удалось подтвердить, что '$date' соответствует формату '$format'. . Ошибка:
                {$e->getMessage()}");
        }
    }

}
