<x-layouts.app :title="'Kategori Produk'">
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-7">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Daftar kategori</h2>
            <a href="{{ route('categories.create') }}"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg mb-4 inline-block">
                + Tambah kategori
            </a>
        </div> 

        {{-- Flash Messages --}}
        @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
            {{ session('error') }}
        </div>
        @endif

        {{-- Table --}}
        <table class="min-w-full bg-white shadow-md rounded-lg">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-b text-left">Nama Kategori</th>
                    <th class="px-4 py-2 border-b text-left">Status Sinkron</th>
                    <th class="px-4 py-2 border-b text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 border-b">{{ $category->name }}</td>
                    <td class="px-4 py-2 border-b">
                        @if ($category->hub_category_id)
                        <span class="text-green-600">Tersinkron (ID: {{ $category->hub_category_id }})</span>
                        @else
                        <span class="text-red-600">Belum Sinkron</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 border-b">
                        @if (!$category->hub_category_id)
                        <button
                            x-data="{ categoryId: '{{ $category->id }}' }"
                            x-on:click="
        const url = `/category/${categoryId}/sync-to-hub`;
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
                    console.error('❌ Error Response:', err); // TAMPILKAN ERROR DI CONSOLE
                    throw new Error(err.message || 'Gagal sinkron kategori');
                });
            }
            return response.json();
        })
        .then(data => {
            alert(data.message + '\nHub Category ID: ' + data.hub_category_id);
            location.reload();
        })
        .catch(error => {
            console.error('❌ CATCH ERROR:', error); // JIKA ERROR JARINGAN ATAU LAINNYA
            alert('Gagal sinkronisasi kategori ke Hub: ' + error.message);
        });
    "
                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                            Sinkronkan ke Hub
                        </button>

                        @else
                        <span class="text-gray-500 text-sm">✓</span>
                        @endif
                    </td>
                    <td>
                        <form id="sync-category-{{ $category->id }}">
                            @csrf
                            <input type="hidden" name="is_active" value="@if($category->hub_category_id) 1 @else 0 @endif">
                            @if($category->hub_category_id)
                            <flux:switch checked onchange="syncCategory('{{ $category->id }}', true)" />
                            @else
                            <flux:switch onchange="syncCategory('{{ $category->id }}', false)" />
                            @endif
                        </form>
                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
    <script>
        function syncCategory(categoryId, isActive) {
            const form = document.getElementById(`sync-category-${categoryId}`);
            const csrfToken = form.querySelector('input[name="_token"]').value;

            fetch(`/category/sync/${categoryId}`, {
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
                            throw new Error(err.message || 'Gagal sinkron kategori');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    alert(data.message || 'Kategori disinkronkan');
                })
                .catch(error => {
                    console.error('❌ Error:', error);
                    alert('Gagal sinkronisasi kategori: ' + error.message);
                });
        }
    </script>

</x-layouts.app>