<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Domain\Interfaces\CartManagerInterface;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Raketa\BackendTestTask\View\CartView;

readonly class GetCartController extends BaseController
{
    public function __construct(
        private CartView $cartView,
        private CartManagerInterface $cartManager,
        private ProductRepository $productRepository,
    ) {
    }

    public function __invoke(RequestInterface $request): ResponseInterface
    {
        $cart = $this->cartManager->getCart($this->getCustomer()?->getId() ?: session_id());

        $productUuids = $cart->getProductUuids();
        $products = $this->productRepository->getByUuids($productUuids);

        return $this->asJson($this->cartView->toArray($cart, $products, $this->getCustomer()));
    }
}
