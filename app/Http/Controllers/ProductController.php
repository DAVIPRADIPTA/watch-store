<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\HubApiService;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function sync($id, Request $request)
    {
        $product = Product::findOrFail($id);

        $response = Http::post('https://api.phb-umkm.my.id/api/product/sync', [
            'client_id' => env('HUB_CLIENT_ID'),
            'client_secret' => env('HUB_CLIENT_SECRET'),
            'seller_product_id' => (string) $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
            // 'sku' => $product->sku,
            'image' => $product->image_url,
            'is_visible' => $request->is_active == 1 ? false : true,
            'category_id' => (string) $product->category->hub_category_id,
        ]);

        if ($response->successful() && isset($response['product_id'])) {
            $product->hub_product_id = $request->is_visible == 1 ? null : $response['product_id'];
            $product->save();
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Produk berhasil disinkronkan']);
        }

        session()->flash('successMessage', 'Product Synced Successfully');
        return redirect()->back();
    }
    protected $hubApiService;

    public function __construct(HubApiService $hubApiService)
    {
        $this->hubApiService = $hubApiService;
    }

    public function index()
    {
        $products = Product::with('category')->orderBy('created_at', 'desc')->get();
        return view('products.index', compact('products'));
    }

    public function toggleVisibility(Request $request, Product $product)
    {
        $request->validate([
            'is_on' => 'required|boolean',
        ]);

        if (empty($product->hub_product_id)) {
            return response()->json(['message' => 'Produk belum tersinkron ke Hub.'], 400);
        }

        try {
            DB::beginTransaction();

            $hubResponse = $this->hubApiService->updateProductVisibility(
                $product->hub_product_id,
                ['is_visible' => $request->is_on]
            );

            $product->is_visible = $request->is_on;
            $product->save();

            DB::commit();

            return response()->json([
                'message' => 'Status visibilitas produk berhasil diperbarui.',
                'hub_response' => $hubResponse
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal update visibilitas produk: " . $e->getMessage());
            return response()->json(['message' => 'Gagal update visibilitas produk.'], 500);
        }
    }

    public function syncProductToHub(Request $request, Product $product)
    {
        try {
            DB::beginTransaction();

            $productData = [
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'stock' => $product->stock,
                'category_id' => $product->category->hub_category_id ?? null,
                'is_visible' => $product->is_visible,
            ];

            $hubResponse = $this->hubApiService->createProduct($productData);

            $product->hub_product_id = $hubResponse['product_id']; // pastikan key sesuai
            $product->save();

            DB::commit();

            return response()->json([
                'message' => 'Produk berhasil disinkronkan ke Hub.',
                'hub_product_id' => $product->hub_product_id,
                'hub_response' => $hubResponse
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal sinkron produk ke Hub: " . $e->getMessage());
            return response()->json(['message' => 'Gagal sinkron produk ke Hub.'], 500);
        }
    }

    public function deleteProductFromHub(Request $request, Product $product)
    {
        if (empty($product->hub_product_id)) {
            return response()->json(['message' => 'Produk belum disinkronkan ke Hub.'], 400);
        }

        try {
            DB::beginTransaction();

            $hubResponse = $this->hubApiService->deleteProduct($product->hub_product_id);

            $product->hub_product_id = null;
            $product->save();

            DB::commit();

            return response()->json([
                'message' => 'Produk berhasil dihapus dari Hub.',
                'hub_response' => $hubResponse
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal hapus produk dari Hub: " . $e->getMessage());
            return response()->json(['message' => 'Gagal hapus produk dari Hub.'], 500);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
