<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain;

final class Cart
{
    /**
     * @param CartItem[] $items
     */
    public function __construct(
        readonly private string $uuid,
        private array $items,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(CartItem $item): void
    {
        // Проверяем на дублирование товара(учитываем что цена может быть разная, например, по акции)
        foreach ($this->items as $cartItem) {
            if (
                $cartItem->getProductUuid() === $item->getProductUuid()
                && bccomp($cartItem->getPrice(), $item->getPrice(), 2) === 0
            ) {
                $cartItem->setQuantity($cartItem->getQuantity() + $item->getQuantity());
                return;
            }
        }

        $this->items[] = $item;
    }

    public function getTotalPrice(): string
    {
        return array_reduce(
            $this->items,
            static fn (string $totalPrice, CartItem $item): string => bcadd(
                $totalPrice,
                bcmul($item->getPrice(), (string)$item->getQuantity(), 2),
                2
            ),
            '0'
        );
    }

    public function getProductUuids(): array
    {
        return array_map(static fn (CartItem $item): string => $item->getProductUuid(), $this->items);
    }
}
