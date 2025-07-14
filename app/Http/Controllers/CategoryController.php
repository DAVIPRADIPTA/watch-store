<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HubCategoryService;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CategoryController extends Controller
{
    

    public function sync($id, Request $request)
    {
        $category = Category::findOrFail($id);

        $response = Http::post('https://api.phb-umkm.my.id/api/product-category/sync', [
            'client_id' => env('HUB_CLIENT_ID'),
            'client_secret' => env('HUB_CLIENT_SECRET'),
            'seller_product_category_id' => (string) $category->id,
            'name' => $category->name,
            'is_active' => $request->is_active == 1 ? false : true,
        ]);

        if ($response->successful() && isset($response['product_category_id'])) {
            $category->hub_category_id = $request->is_active == 1 ? null : $response['product_category_id'];
            $category->save();
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Kategori berhasil disinkronkan ke Hub.']);
        }

        // fallback jika bukan request AJAX
        session()->flash('successMessage', 'Kategori berhasil disinkronkan.');
        return redirect()->back();
    }

    public function syncToHub($id, HubCategoryService $hubCategoryService)
    {
        $category = Category::findOrFail($id);

        if ($category->hub_category_id) {
            return back()->with('error', 'Kategori sudah tersinkron ke Hub.');
        }

        try {
            $hubResponse = $hubCategoryService->createCategory([
                'name' => $category->name,
            ]);

            $category->hub_category_id = $hubResponse['category_id']; // sesuaikan key-nya
            $category->save();

            return back()->with('success', 'Kategori berhasil disinkronkan ke Hub.');
        } catch (\Throwable $e) {
            Log::error('Gagal sync kategori: ' . $e->getMessage());
            return response()->json([
                'message' => 'Gagal sinkronisasi kategori ke Hub.',
                'error' => $e->getMessage(), // ini akan tampil di console
            ], 500);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('created_at', 'desc')->get();

        return view('categories.index', compact('categories'));
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
