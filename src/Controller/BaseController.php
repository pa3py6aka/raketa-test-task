<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller;

use Raketa\BackendTestTask\Domain\Customer;

abstract readonly class BaseController
{
    protected function asJson(array $data, int $statusCode = 200): JsonResponse
    {
        $response = new JsonResponse();
        $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        return $response->withStatus($statusCode);
    }

    /**
     * Метод-заглушка для получения авторизованного пользователя
     */
    protected function getCustomer(): ?Customer
    {
        return null;
    }
}