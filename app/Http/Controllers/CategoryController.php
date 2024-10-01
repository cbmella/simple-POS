<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // Obtener todas las categorías
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    // Obtener una categoría por ID
    public function show($id)
    {
        $category = Category::find($id);
        if ($category) {
            return response()->json($category);
        } else {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }

    // Crear una nueva categoría
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:categories'
        ]);

        $category = Category::create($request->all());
        return response()->json($category, 201);
    }

    // Actualizar una categoría
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'sometimes|required|unique:categories,name,' . $id
        ]);

        $category = Category::find($id);

        if ($category) {
            $category->update($request->all());
            return response()->json($category);
        } else {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }

    // Eliminar una categoría
    public function destroy($id)
    {
        $category = Category::find($id);

        if ($category) {
            $category->delete();
            return response()->json(['message' => 'Category deleted']);
        } else {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }
}
