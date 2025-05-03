<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Repository;

use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Domain\Interfaces\CartManagerInterface;
use Raketa\BackendTestTask\Infrastructure\ConnectorFactory;
use Raketa\BackendTestTask\Infrastructure\ConnectorInterface;
use Raketa\BackendTestTask\Infrastructure\Exceptions\ConnectorException;
use Ramsey\Uuid\Uuid;

class CartManager implements CartManagerInterface
{
    private ConnectorInterface $connector;

    public function __construct(private LoggerInterface $logger, ConnectorFactory $connectorFactory)
    {
        $this->connector = $connectorFactory->getConnector();
    }

    /**
     * @inheritdoc
     */
    public function saveCart(Cart $cart, string $key): bool
    {
        try {
            return $this->connector->set($key, $cart);
        } catch (ConnectorException $exception) {
            $this->logger->error('Ошибка сохранения корзины', ['exception' => $exception]);
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function getCart(string $key): Cart
    {
        try {
            $cart = $this->connector->get($key);
        } catch (ConnectorException $exception) {
            $this->logger->error('Ошибка при получении корзины', ['key' => $key, 'exception' => $exception]);
        }

        return $cart ?? new Cart(Uuid::uuid4()->toString(), []);
    }
}
