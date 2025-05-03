<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Raketa\BackendTestTask\View\ProductsView;

readonly class GetProductsController extends BaseController
{
    public function __construct(
        private ProductsView $productsView,
        private ProductRepository $productRepository,
    ) {
    }

    public function __invoke(RequestInterface $request): ResponseInterface
    {
        $rawRequest = json_decode($request->getBody()->getContents(), true);

        $products = $this->productRepository->getByCategory($rawRequest['category']);

        return $this->asJson($this->productsView->toArray($products));
    }
}
