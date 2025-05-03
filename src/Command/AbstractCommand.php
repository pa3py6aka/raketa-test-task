<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Command;

use Psr\Container\ContainerInterface;
use stdClass;

abstract class AbstractCommand implements CommandInterface
{
    private ?ContainerInterface $container = null;

    // Заглушка
    protected function getContainer(): ContainerInterface
    {
        if (null === $this->container) {
            $this->container = new stdClass(); // Подтягиваем нужный объект через DI
        }

        return $this->container;
    }
}