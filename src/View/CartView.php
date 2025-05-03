<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Domain\Customer;
use Raketa\BackendTestTask\Domain\Entity\Product;

readonly class CartView
{
    /**
     * @param Product[] $products
     */
    public function toArray(Cart $cart, array $products, ?Customer $customer): array
    {
        $data = [
            'uuid' => $cart->getUuid(),
            'items' => [],
            'customer' => $customer ? [
                'id' => $customer->getId(),
                'name' => implode(' ', [
                    $customer->getLastName(),
                    $customer->getFirstName(),
                    $customer->getMiddleName(),
                ]),
                'email' => $customer->getEmail(),
            ] : null,
        ];

        foreach ($cart->getItems() as $item) {
            $product = $products[$item->getProductUuid()] ?? null;
            if ($product === null) {
                continue;
            }

            $data['items'][] = [
                'uuid' => $item->getUuid(),
                'price' => $item->getPrice(),
                'total' => $item->getTotalPrice(),
                'quantity' => $item->getQuantity(),
                'product' => [
                    'id' => $product->getId(),
                    'uuid' => $product->getUuid(),
                    'name' => $product->getName(),
                    'thumbnail' => $product->getThumbnail(),
                    'price' => $product->getPrice(),
                ],
            ];
        }

        $data['total'] = $cart->getTotalPrice();

        return $data;
    }
}
