<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Raketa\BackendTestTask\Domain\Entity\Product;
use Raketa\BackendTestTask\Infrastructure\Exceptions\ProductNotFoundException;

class ProductRepository
{
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * @throws ProductNotFoundException | Exception
     */
    public function getByUuid(string $uuid): Product
    {
        $row = $this->connection->fetchOne('SELECT * FROM products WHERE uuid = ?', [$uuid]);

        if ($row === false) {
            throw ProductNotFoundException::create($uuid);
        }

        return $this->make($row);
    }

    /**
     * @return Product[] Массив товаров проиндексированный по uuid
     * @throws Exception
     */
    public function getByUuids(array $uuids): array
    {
        $rows = $this->connection->fetchAllAssociativeIndexed(
            'SELECT uuid, products.* FROM products WHERE uuid IN ?',
            [$uuids]
        );

        return array_map([$this, 'make'], $rows);
    }

    /**
     * @return Product[]
     * @throws Exception
     */
    public function getByCategory(string $category): array
    {
        return array_map(
            [$this, 'make'],
            $this->connection->fetchAllAssociative(
                'SELECT id FROM products WHERE is_active = :is_active AND category =:category',
                [
                    'is_active' => 1,
                    'category' => $category,
                ]
            )
        );
    }

    private function make(array $row): Product
    {
        return new Product(
            (int)$row['id'],
            (string)$row['uuid'],
            (bool)$row['is_active'],
            (string)$row['category'],
            (string)$row['name'],
            (string)$row['description'],
            (string)$row['thumbnail'],
            number_format($row['price'], 2, '.', ''), // если с БД приходит как float
        );
    }
}
