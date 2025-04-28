<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;  // Ларевельний фасад Response

class ProductController extends Controller
{
    // Масив продуктів, який ми будемо використовувати
    private const PRODUCTS = [
        ['id' => 1, 'name' => 'Phone', 'description' => 'Smartphone', 'price' => 599],
        ['id' => 2, 'name' => 'Laptop', 'description' => 'Ultrabook', 'price' => 999],
    ];

    // Отримання всіх продуктів
    public function index()
    {
        return response()->json(['data' => self::PRODUCTS], Response::HTTP_OK);
    }

    // Отримання одного продукту по ID
    public function show($id)
    {
        $product = collect(self::PRODUCTS)->firstWhere('id', (int)$id);

        if (!$product) {
            return response()->json(['error' => "Product with id $id not found"], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['data' => $product], Response::HTTP_OK);
    }

    // Створення нового продукту
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'price' => 'required|numeric|min:0',
        ]);

        $data = $validated;
        $data['id'] = rand(3, 100);  // Генеруємо випадковий ID для нового продукту

        return response()->json(['data' => $data], Response::HTTP_CREATED);
    }

   // Оновлення продукту
public function update(Request $request, $id)
{
    $product = collect(self::PRODUCTS)->firstWhere('id', (int)$id);

    if (!$product) {
        return response()->json(['error' => "Product with id $id not found"], Response::HTTP_NOT_FOUND);
    }

    $validated = $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'description' => 'sometimes|required|string|max:500',
        'price' => 'sometimes|required|numeric|min:0',
    ]);

    $data = $validated;
    $product = array_merge($product, $data);  // Оновлюємо продукт

    return response()->json(['updated' => $product], Response::HTTP_OK);
}

    // Видалення продукту
    public function destroy($id)
    {
        $product = collect(self::PRODUCTS)->firstWhere('id', (int)$id);

        if (!$product) {
            return response()->json(['error' => "Product with id $id not found"], Response::HTTP_NOT_FOUND);
        }

        // Видаляємо продукт з масиву
        $products = collect(self::PRODUCTS)->filter(fn($item) => $item['id'] !== (int)$id)->values()->all();

        return response()->json(['message' => "Product with id $id deleted", 'products' => $products], Response::HTTP_OK);
    }
}
