<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductBulkController extends Controller
{
    public function store_product(Request $request)
    {
        // Expect an array of products
        $products = $request->validate([
            'products' => 'required|array',
            'products.*.heading'      => 'required|string',
            'products.*.topic'        => 'required|string',
            'products.*.price'        => 'required|string',
            'products.*.product_type' => 'required|string',
            'products.*.stock'        => 'required|string',
            'products.*.image'        => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $insertedProducts = [];

        foreach ($products['products'] as $item) {

            $imageUrl = null;

            if (isset($item['image']) && $item['image'] instanceof \Illuminate\Http\UploadedFile) {
                $image = $item['image'];
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $filename);
                $imageUrl = asset('images/' . $filename);
            }

            $id = DB::table('products')->insertGetId([
                'heading'      => $item['heading'],
                'topic'        => $item['topic'],
                'price'        => $item['price'],
                'product_type' => $item['product_type'],
                'stock'        => $item['stock'],
                'image'        => $imageUrl,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            $insertedProducts[] = DB::table('products')->where('id', $id)->first();
        }

        return response()->json($insertedProducts, 201);
    }


   public function index()
{
    $products = DB::table('products')->orderBy('id', 'desc')->get();
    return response()->json($products, 200);
}

}
