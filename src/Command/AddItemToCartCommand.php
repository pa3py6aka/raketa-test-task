<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Command;

use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Domain\CartItem;
use Raketa\BackendTestTask\Domain\Entity\Product;
use Raketa\BackendTestTask\Domain\Interfaces\CartManagerInterface;
use Ramsey\Uuid\Uuid;

class AddItemToCartCommand extends AbstractCommand
{
    public function __construct(
        private readonly string $cartKey,
        private readonly Product $product,
        private readonly int $quantity,
    ) {
    }

    public function execute(): Cart|false
    {
        /** @var CartManagerInterface $cartManager */
        $cartManager = $this->getContainer()->get(CartManagerInterface::class);

        $cart = $cartManager->getCart($this->cartKey);
        $cart->addItem(
            new CartItem(
                Uuid::uuid4()->toString(),
                $this->product->getUuid(),
                $this->product->getPrice(),
                $this->quantity,
            )
        );

        return $cartManager->saveCart($cart, $this->cartKey);
    }
}