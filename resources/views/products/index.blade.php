<x-layouts.app :title="__('Dashboard')">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Daftar Produk</h1>
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
                        <td class="py-3 px-4 border-b border-gray-200">
                            @if ($product->hub_product_id)
                            <div x-data="{
 isOn: {{ $product->is_visible ? 'true' : 'false' }},
 productId: {{ $product->id }},
 hubProductId: {{ $product->hub_product_id }},
 toggleVisibility() {
 const url = `/api/products/${this.productId}/toggle-visibility`;
 const csrfToken =
document.querySelector('meta[name=" csrf-token"]').getAttribute('content');
                                fetch(url, {
                                method: 'PUT' ,
                                headers: { 'Content-Type' : 'application/json' , 'Accept' : 'application/json' , 'X-CSRF-TOKEN' : csrfToken,
                                // 'Authorization' : 'Bearer ' + yourAdminToken, // Jika perlu otentikasi admin
                                toko
                                },
                                body: JSON.stringify({ is_on: this.isOn })
                                })
                                .then(response=> {
                                if (!response.ok) {
                                return response.json().then(err => { throw new Error(err.message || 'Failed to
                                toggle visibility'); });
                                }
                                return response.json();
                                })
                                .then(data => {
                                alert(data.message);
                                })
                                .catch(error => {
                                console.error('Error:', error);
                                alert('Gagal mengubah visibilitas produk: ' + error.message);
                                this.isOn = !this.isOn; // Kembalikan status jika gagal
                                });
                                }
                                }">
                                {{-- Toggle switch dari FluxUI/Tailwind Components --}}
                                <button
                                    type="button"
                                    x-on:click="isOn = !isOn; toggleVisibility()"
                                    :aria-checked="isOn"
                                    :class="isOn ? 'bg-indigo-600' : 'bg-gray-200'"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2
border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600
focus:ring-offset-2"
                                    role="switch">
                                    <span class="sr-only">Enable notifications</span>
                                    <span
                                        :aria-hidden="true"
                                        :class="isOn ? 'translate-x-5' : 'translate-x-0'"
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white
shadow ring-0 transition duration-200 ease-in-out"></span>
                                </button>
                            </div>
                            @else
                            <span class="text-red-500">Belum Disinkronkan</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 border-b border-gray-200">
                            @if (empty($product->hub_product_id))
                            <button
                                x-data="{ productId: '{{ $product->id }}' }"
                                x-on:click="
        const url = `/api/products/${productId}/sync-to-hub`;
        const csrfToken = document.querySelector('meta[name=\'csrf-token\']').getAttribute('content');

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({})
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Failed to sync product');
                });
            }
            return response.json();
        })
        .then(data => {
            alert(data.message + '\nHub Product ID: ' + data.hub_product_id);
            location.reload();
        })
        .catch(error => {
            console.error('Error syncing product:', error);
            alert('Gagal sinkronisasi produk ke Hub: ' + error.message);
        });
    "
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                Sinkronkan ke Hub
                            </button>

                            @else
                            <button
                                x-data="{ productId: {{ $product->id }} }"
                                x-on:click="
 if (!confirm('Apakah Anda yakin ingin menghapus produk ini dari Hub?')) { return; }
 const url = `/api/products/${productId}/delete-from-hub`;
 const csrfToken =
document.querySelector('meta[name='csrf-token']').getAttribute('content');
 fetch(url, {
 method: 'DELETE',
 headers: {
 'Content-Type': 'application/json',
 'Accept': 'application/json',
 'X-CSRF-TOKEN': csrfToken,
 },
 })
.then(response => {
 if (!response.ok) {
 return response.json().then(err => { throw new Error(err.message || 'Failed to
delete from Hub'); });
 }
return response.json();
 })
.then(data => {
 alert(data.message);
location.reload();
 })
.catch(error => {
 console.error('Error deleting product:', error);
 alert('Gagal menghapus produk dari Hub: ' + error.message);
 });
 "
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm ml-2">
                                Hapus dari Hub
                            </button>
                            @endif
                            {{-- Tombol edit/hapus lokal lainnya --}}
                            <a href="{{ route('products.edit', $product->id) }}" class="bg-green-500 hover:bg-green-700
text-white font-bold py-2 px-4 rounded text-sm ml-2">Edit</a>
                        </td>
                        <td>
                            <form id="sync-product-{{ $product->id }}">
                                @csrf
                                <input type="hidden" name="is_active" value="@if($product->hub_product_id) 1 @else 0 @endif">
                                @if($product->hub_product_id)
                                <flux:switch checked onchange="syncProduct('{{ $product->id }}', true)" />
                                @else
                                <flux:switch onchange="syncProduct('{{ $product->id }}', false)" />
                                @endif
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

<!-- <x-layouts.app :title="' Produk'">
<th>On/Off</th>


  <td>
    <form id="sync-product-{{ $product->id }}" action="{{ route('products.sync', $product->id) }}" method="POST">
          @csrf
          <input type="hidden" name="is_active" value="@if($product->hub_product_id) 1 @else 0 @endif" >
              @if($product->hub_product_id)
                 <flux:switch checked onchange="document.getElementById('sync-product-{{ $product->id }}').submit()" />
              @else
                 <flux:switch onchange="document.getElementById('sync-product-{{ $product->id }}').submit()" />
              @endif
   </form>
  </td>
</x-layouts.app> -->