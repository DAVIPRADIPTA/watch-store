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
                    <!-- <th class="px-4 py-2 border-b text-left">Status Sinkron</th> -->
                    <th>Status</th>
                    <th>singkron</th>
                    <th class="px-4 py-2 border-b text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 border-b">{{ $category->name }}</td>
                    <!-- <td>
                        <form id="sync-category-{{ $category->id }}">
                            @csrf
                            <input type="hidden" name="is_active" value="{{ $category->is_active ? 1 : 0 }}">
                            @if($category->hub_category_id)
                            <flux:switch checked onchange="syncCategory('{{ $category->id }}', true)" />
                            @else
                            <flux:switch onchange="syncCategory('{{ $category->id }}', false)" />
                            @endif
                        </form>
                    </td> -->

                    {{-- Status --}}
                        <td class="border px-4 py-2 text-center">
                            <form action="{{ route('categories.toggleStatus', $category->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1 rounded text-white {{ $category->is_active ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }}">
                                    {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>

                        {{-- Sinkronisasi --}}
                        <td class="border px-4 py-2 text-center">
                            <form action="{{ route('categories.sync', $category) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1 rounded text-white bg-green-600 hover:bg-green-700">
                                    Sinkronkan
                                </button>
                            </form>
                        </td>

                    <td class="py-3 px-4 border-b border-gray-200">
                        <a href="{{ route('categories.edit', $category->id) }}" class="bg-green-500 hover:bg-green-700
text-white font-bold py-2 px-4 rounded text-sm ml-2">Edit</a>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
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