<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure;

use Raketa\BackendTestTask\Infrastructure\Exceptions\ConnectorException;
use Redis;
use RedisException;

class RedisConnectionFactory implements ConnectionFactoryInterface
{
    public function __construct(
        private readonly string $host,
        private readonly int $port,
        private readonly ?string $password = null,
        private readonly ?int $dbindex = null,
    ) {
    }

    /**
     * @throws ConnectorException
     */
    public function build(): RedisConnector
    {
        $redis = new Redis();

        try {
            $isConnected = $redis->isConnected();
            if (! $isConnected && $redis->ping('Pong')) {
                $isConnected = $redis->connect($this->host, $this->port);
            }
            if (! $isConnected) {
                throw new RedisException('Cannot connect to Redis server');
            }

            if ($this->password !== null && $redis->auth($this->password) === false) {
                throw new RedisException('Authentication failed');
            }

            if ($this->dbindex !== null && $redis->select($this->dbindex) === false) {
                throw new RedisException('Cannot select a database');
            }

            return new RedisConnector($redis);
        } catch (RedisException $exception) {
            throw new ConnectorException('RedisConnectionFactory error', $exception->getCode(), $exception);
        }
    }
}