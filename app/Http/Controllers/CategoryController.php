<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HubCategoryService;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
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
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        // $validated['slug'] = Str::slug($validated['name']);
        $validated['id'] = Str::uuid();

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan');
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
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->name;
        // $category->slug = Str::slug($request->name);
        $category->save();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function sync(Request $request, Category $category)
    {
        $response = Http::post('https://api.phb-umkm.my.id/api/product-category/sync', [
            'client_id' => env('HUB_CLIENT_ID'),
            'client_secret' => env('HUB_CLIENT_SECRET'),
            'seller_product_category_id' => (string) $category->id,
            'name' => $category->name,
            'description' => $category->description,
            'is_active' => $category->is_active == 1 ? true : false,
        ]);

        if ($response->successful() && isset($response['product_category_id'])) {
            $category->hub_category_id = $request->is_active == 1 ? null : $response['product_category_id'];
            $category->save();
        }

        return redirect()->back()->with('success', 'Sinkronisasi kategori berhasil.');
    }
    public function toggleStatus(Category $category)
    {
        $category->is_active = !$category->is_active;
        $category->save();

        // Update semua novel yang termasuk dalam kategori ini
        $category->products()->update(['is_active' => $category->is_active]);

        return redirect()->route('categories.index')->with('success', 'Status kategori dan novel di dalamnya berhasil diperbarui.');
    }
}
