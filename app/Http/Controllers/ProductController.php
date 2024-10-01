<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // Obtener todos los productos
    public function index()
    {
        $products = Product::with('category')->get(); // Incluyendo categoría
        return response()->json($products);
    }

    // Obtener un producto por ID
    public function show($id)
    {
        $product = Product::with('category')->find($id);
        if ($product) {
            return response()->json($product);
        } else {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    // Crear un nuevo producto
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'sku' => 'required|unique:products',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id'
        ]);

        $product = Product::create($request->all());
        return response()->json($product, 201);
    }

    // Actualizar un producto
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'sometimes|required',
            'sku' => 'sometimes|required|unique:products,sku,' . $id,
            'price' => 'sometimes|required|numeric',
            'quantity' => 'sometimes|required|integer',
            'category_id' => 'sometimes|required|exists:categories,id'
        ]);

        $product = Product::find($id);

        if ($product) {
            $product->update($request->all());
            return response()->json($product);
        } else {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    // Eliminar un producto
    public function destroy($id)
    {
        $product = Product::find($id);

        if ($product) {
            $product->delete();
            return response()->json(['message' => 'Product deleted']);
        } else {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }
}
