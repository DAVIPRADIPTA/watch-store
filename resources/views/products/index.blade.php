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
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Gambar
                        </th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600
uppercase tracking-wider">Nama Produk</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600
uppercase tracking-wider">Harga</th>
                        <th>status</th>
                        <th>sigkron</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600
uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 border-b border-gray-200">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded">
                            @else
                                <span class="text-gray-400 text-sm">Tidak ada gambar</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 border-b border-gray-200">{{ $product->name }}</td>
                        <td class="py-3 px-4 border-b border-gray-200">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                        {{-- status --}}
                        <td class="border px-4 py-2 text-center">
                            <form action="{{ route('products.toggleStatus', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1 rounded text-white {{ $product->is_active ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }}">
                                    {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>

                        {{-- Sinkronisasi --}}
                        <td class="border px-4 py-2 text-center">
                            <form action="{{ route('products.sync', $product) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1 rounded text-white bg-green-600 hover:bg-green-700">
                                    Sinkronkan
                                </button>
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
</x-layouts.app>