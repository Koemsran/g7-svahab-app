<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index(): JsonResponse
    {
        $categories = Category::all();
        return response()->json($categories);
    }
    

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create($request->all());
        return response()->json([
            'message' => 'Category created successfully',
            'data' => $category,
        ], 201);
    }

    /**
     * Display the specified category.
     */
    public function show($categoryId)
    {
        $category = Category::find($categoryId);
        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $products = Product::where('category_id', $categoryId)->get();
        return response()->json([
            'category' => $category,
            'products' => $products
        ]);
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());
        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $category,
        ]);
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id): JsonResponse
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json([
            'message' => 'Category deleted successfully',
        ], 204);
    }
}
