<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain\Interfaces;

use Raketa\BackendTestTask\Domain\Cart;

/**
 * Интерфейс описывающий методы работы с корзиной
 */
interface CartManagerInterface
{
    /**
     * Сохраняет корзину в хранилище
     */
    public function saveCart(Cart $cart, string $key): bool;

    /**
     * Получение корзины из хранилища
     */
    public function getCart(string $key): Cart;
}