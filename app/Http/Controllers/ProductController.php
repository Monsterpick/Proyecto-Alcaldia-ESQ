<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function dropzone(Request $request, Product $product)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        $path = Storage::disk('public')->put('/images/products', $request->file('file'));

        $image = $product->images()->create([
            'path' => $path,
            'size' => $request->file('file')->getSize(),
        ]);

        return response()->json([
            'id' => $image->id,
            'path' => $image->path,
        ]);
    }
}
