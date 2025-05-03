<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain;

final class CartItem
{
    public function __construct(
        private readonly string $uuid,
        private readonly string $productUuid,
        private readonly string $price,
        private int $quantity,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getProductUuid(): string
    {
        return $this->productUuid;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getTotalPrice(): string
    {
        return bcmul($this->price, (string)$this->quantity, 2);
    }
}
