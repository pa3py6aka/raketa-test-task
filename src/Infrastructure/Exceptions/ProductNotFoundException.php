<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\Exceptions;

class ProductNotFoundException extends \Exception
{
    public static function create(string $uuid): self
    {
        return new self(sprintf('Товар не найден в БД. [uuid: %s]', $uuid));
    }
}