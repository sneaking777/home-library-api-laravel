<?php

namespace App\FakerProviders;

use Faker\Provider\Base;
use InvalidArgumentException;

/**
 * Класс PatronymicFakerProvider
 *
 * Генерация рандомных имён отчеств
 *
 * @package App\FakerProviders
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 01.09.2024 11:55
 */
class PatronymicFakerProvider extends Base
{

    /**
     * Склонение имени в русском языке для отчества.
     *
     * @param string $gender male - мужской пол, female - женский
     * @param string $fatherName имя отчества
     * @return string
     */
    public function makeRussianPatronymic(string $gender, string $fatherName): string
    {
        if (!in_array($gender, ['male', 'female'])) {
            throw new InvalidArgumentException(__('invalid_argument.gender'));
        }
        $end = mb_substr($fatherName, -1);
        if ($end === 'й') {
            $baseName = mb_substr($fatherName, 0, -1);
            $suffix = $gender === 'male' ? 'евич' : 'ьевна';
        } else if ($end === 'ь') {
            $suffix = $gender === 'male' ? 'ич' : 'на';
            $baseName = $fatherName;
        } else if ($end === 'я') {
            $suffix = $gender === 'male' ? 'ич' : 'ьевна';
            $baseName = $fatherName;

        } else if ($end === 'а') {
            $suffix = $gender === 'male' ? 'ич' : 'на';
            $baseName = $fatherName;
        } else {
            $suffix = $gender === 'male' ? 'ович' : 'овна';
            $baseName = $fatherName;
        }

        return $baseName . $suffix;

    }
}
