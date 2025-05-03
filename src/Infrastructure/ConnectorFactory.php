<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure;

final class ConnectorFactory
{
    public function __construct(private ConnectionFactoryInterface $connectionFactory)
    {
    }

    public function getConnector(): ConnectorInterface
    {
        return $this->connectionFactory->build();
    }
}