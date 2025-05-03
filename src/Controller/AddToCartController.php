<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Command\AddItemToCartCommand;
use Raketa\BackendTestTask\Infrastructure\Exceptions\ProductNotFoundException;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Raketa\BackendTestTask\View\CartView;

readonly class AddToCartController extends BaseController
{
    public function __construct(
        private ProductRepository $productRepository,
        private CartView $cartView,
    ) {
    }

    public function __invoke(RequestInterface $request): ResponseInterface
    {
        $rawRequest = json_decode($request->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        try {
            $product = $this->productRepository->getByUuid($rawRequest['productUuid']);
        } catch (ProductNotFoundException $exception) {
            return $this->asJson(['status' => 'error', 'message' => 'Товар не найден:('], 404);
        }

        $result = (new AddItemToCartCommand(
            $this->getCustomer()?->getId() ?: session_id(),
            $product,
            $rawRequest['quantity']
        ))->execute();

        if ($result === false) {
            return $this->asJson(['status' => 'error'], 422);
        }

        $products = $this->productRepository->getByUuids($result->getProductUuids());

        return $this->asJson([
            'status' => 'success',
            'cart' => $this->cartView->toArray($result, $products, $this->getCustomer()),
        ]);
    }
}
