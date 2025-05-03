<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure;

use Raketa\BackendTestTask\Domain\Cart;

interface ConnectorInterface
{
    public function get(string $key): ?Cart;

    public function set(string $key, Cart $value): bool;

    public function has($key): bool;
}