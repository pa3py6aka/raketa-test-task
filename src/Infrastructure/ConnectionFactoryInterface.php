<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure;

interface ConnectionFactoryInterface
{
    public function build(): ConnectorInterface;
}