<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Command;

interface CommandInterface
{
    public function execute(): mixed;
}