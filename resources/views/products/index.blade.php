<x-layouts.app :title="__('Dashboard')">
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-7">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Daftar Produk</h2>
            <a href="{{ route('products.create') }}"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg mb-4 inline-block">
                + Tambah Produk
            </a>
        </div> 
        @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif
        @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif
        <div class="overflow-x-auto">

            <table class="min-w-full bg-white shadow-md rounded-lg">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600
uppercase tracking-wider">Nama Produk</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600
uppercase tracking-wider">Harga</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600
uppercase tracking-wider">Visibilitas Produk</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600
uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 border-b border-gray-200">{{ $product->name }}</td>
                        <td class="py-3 px-4 border-b border-gray-200">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>
                            <form id="sync-product-{{ $product->id }}">
                                @csrf
                                <input type="hidden" name="is_visible" value="@if($product->hub_product_id) 1 @else 0 @endif">
                                @if($product->hub_product_id)
                                <flux:switch checked onchange="syncProduct('{{ $product->id }}', true)" />
                                @else
                                <flux:switch onchange="syncProduct('{{ $product->id }}', false)" />
                                @endif
                            </form>
                        </td>
                        <td class="py-3 px-4 border-b border-gray-200">
                            <a href="{{ route('products.edit', $product->id) }}" class="bg-green-500 hover:bg-green-700
text-white font-bold py-2 px-4 rounded text-sm ml-2">Edit</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm ml-2">
                                    Hapus
                                </button>
                            </form>
                        </td>


                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        function syncProduct(productId, isActive) {
            const form = document.getElementById(`sync-product-${productId}`);
            const csrfToken = form.querySelector('input[name="_token"]').value;

            fetch(`/products/sync/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        is_active: isActive ? 1 : 0
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Gagal sinkron produk');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    alert(data.message || 'Produk berhasil disinkronkan');
                })
                .catch(error => {
                    console.error('❌ Error:', error);
                    alert('Gagal sinkronisasi produk: ' + error.message);
                });
        }
    </script>

</x-layouts.app>