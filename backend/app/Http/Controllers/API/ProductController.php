<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category', 'colors', 'sizes'])->get();
        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        if (Auth::check()) {
            $validatedData = $request->validated();

            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/images', $imageName);

            $product = new Product();
            $product->name = $validatedData['name'];
            $product->description = $validatedData['description'];
            $product->price = $validatedData['price'];
            $product->image = 'images/' . $imageName;
            $product->category_id = $validatedData['category_id'];
            $product->save();

            // Sync colors and sizes if provided
            if (isset($validatedData['color_ids'])) {
                $product->colors()->sync($validatedData['color_ids']);
            }

            if (isset($validatedData['size_ids'])) {
                $product->sizes()->sync($validatedData['size_ids']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
            ], 201);
        } else {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    }

    public function show($id)
    {
        try {
            $product = Product::with(['category', 'colors', 'sizes', 'discounts'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $product,
                'discounted_price' => $product->discounted_price,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }


    public function update(ProductRequest $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $validatedData = $request->validated();

        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/images', $imageName);
            $product->image = 'images/' . $imageName;
        }

        $product->name = $validatedData['name'];
        $product->description = $validatedData['description'];
        $product->price = $validatedData['price'];
        $product->category_id = $validatedData['category_id'];
        $product->save();

        if (isset($validatedData['color_ids'])) {
            $product->colors()->sync($validatedData['color_ids']);
        }

        if (isset($validatedData['size_ids'])) {
            $product->sizes()->sync($validatedData['size_ids']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (Auth::check()) {
            $product = Product::findOrFail($id);

            // Delete the image from storage
            Storage::delete('public/' . $product->image);

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully',
            ]);
        } else {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    }
    
}
