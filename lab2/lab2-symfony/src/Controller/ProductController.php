<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProductController 
{
    private $products = [
        ['id' => 1, 'name' => 'Product 1', 'description' => 'Description 1', 'price' => 100],
        ['id' => 2, 'name' => 'Product 2', 'description' => 'Description 2', 'price' => 200],
    ];

    // Додайте цей метод для знаходження продукту за його ID
    private function getProductItemById(array $products, string $id)
    {
        return current(array_filter($products, fn($product) => $product['id'] == $id)) ?: null;
    }

    #[Route('/products', name: 'get_products', methods: [Request::METHOD_GET])]
    public function getProducts(): JsonResponse
    {
        return new JsonResponse(['data' => $this->products], status: Response::HTTP_OK);
    }

    #[Route('/products/{id}', name: 'get_product_item', methods: [Request::METHOD_GET])]
    public function getProductItem(string $id): JsonResponse
    {
        $product = $this->getProductItemById($this->products, $id);

        if (!$product) {
            return new JsonResponse(['data' => ['error' => 'Not found product by id ' . $id]], status: Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['data' => $product], status: Response::HTTP_OK);
    }

    #[Route('/products', name: 'post_products', methods: [Request::METHOD_POST])]
    public function createProduct(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $productId = random_int(1, 100);

        $newProductData = [
            'id'            => $productId,
            'name'          => $requestData['name'],
            'description'   => $requestData['description'],
            'price'         => $requestData['price']
        ];

        // Додавання нового продукту до масиву
        $this->products[] = $newProductData;

        return new JsonResponse(['data' => $newProductData], status: Response::HTTP_CREATED);
    }

    #[Route('/products/{id}', name: 'update_product', methods: [Request::METHOD_PUT])]
    public function updateProduct(string $id, Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        // Знайти продукт по ID
        $product = $this->getProductItemById($this->products, $id);

        if (!$product) {
            return new JsonResponse(['data' => ['error' => 'Product not found']], status: Response::HTTP_NOT_FOUND);
        }

        // Оновити продукт
        $product['name'] = $requestData['name'];
        $product['description'] = $requestData['description'];
        $product['price'] = $requestData['price'];

        // Замінити продукт у масиві
        $productIndex = array_search($id, array_column($this->products, 'id'));

        if ($productIndex !== false) {
            $this->products[$productIndex] = $product;
        } else {
            return new JsonResponse(['data' => ['error' => 'Product not found']], status: Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['message' => 'Product updated successfully.'], Response::HTTP_OK);
    }

    #[Route('/products/{id}', name: 'delete_product', methods: [Request::METHOD_DELETE])]
    public function deleteProduct(string $id): JsonResponse
    {
        // Знайти індекс продукту по ID
        $productIndex = array_search($id, array_column($this->products, 'id'));

        if ($productIndex === false) {
            return new JsonResponse(['data' => ['error' => 'Product not found']], status: Response::HTTP_NOT_FOUND);
        }

        // Видалити продукт з масиву
        array_splice($this->products, $productIndex, 1);

        return new JsonResponse(['message' => 'Product deleted successfully.'], Response::HTTP_OK);
    }
}