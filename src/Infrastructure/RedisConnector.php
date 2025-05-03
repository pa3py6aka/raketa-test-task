<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure;

use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Infrastructure\Exceptions\ConnectorException;
use Redis;
use RedisException;

class RedisConnector implements ConnectorInterface
{
    // TODO: Время жизни корзины желательно перенести в конфиг
    private const LIFETIME_TTL = 24 * 60 * 60;

    public function __construct(private readonly Redis $redis)
    {
    }

    /**
     * @throws ConnectorException
     */
    public function get(string $key): ?Cart
    {
        try {
            $cart = $this->redis->get($this->getKey($key));
            return $cart !== false ? unserialize($cart) : null;
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error', $e->getCode(), $e);
        }
    }

    /**
     * @throws ConnectorException
     */
    public function set(string $key, Cart $value): bool
    {
        try {
            return $this->redis->setex($this->getKey($key), self::LIFETIME_TTL, serialize($value));
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error', $e->getCode(), $e);
        }
    }

    public function has($key): bool
    {
        return $this->redis->exists($key);
    }

    private function getKey(string $key): string
    {
        return 'cart:' . $key;
    }
}
