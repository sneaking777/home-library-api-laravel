<?php

namespace App\Responders;

use Illuminate\Http\JsonResponse;

/**
 * Базовый ответчик
 *
 * @package App\Books\Responders
 * @author Alexander Mityukhin <almittt@mail.ru>
 * @date 09.08.2024 16:33
 */
class BaseResponder
{
    /**
     * Создает JsonResponse с предоставленными данными и статусом HTTP.
     *
     * @param array $data
     * @param int $status
     * @return JsonResponse
     */
    public function respond(array $data, int $status): JsonResponse
    {
        return response()->json($data, $status);
    }
}
