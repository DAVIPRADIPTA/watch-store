{{-- resources/views/products/show.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $product->name }} - Watch Store</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-white font-sans">

    <header class="bg-gray-100 px-8 py-6">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="text-xl font-bold tracking-wider text-black uppercase">Watch Store</a>
            <a href="{{ route('home') }}" class="text-sm font-semibold uppercase tracking-widest text-black hover:text-gray-600">
                ← Kembali ke Home
            </a>
        </div>
    </header>

    <main class="container mx-auto px-6 md:px-8 py-16">
        <div class="flex flex-col md:flex-row gap-12 items-center">
            <!-- Gambar Produk -->
            <div class="md:w-1/2 bg-gray-100 p-8 rounded-xl">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                    class="w-full h-auto object-contain">
            </div>

            <!-- Detail Produk -->
            <div class="md:w-1/2">
                <h2 class="font-serif text-5xl uppercase tracking-wide text-gray-900 mb-4">{{ $product->name }}</h2>
                <p class="text-2xl text-gray-600 mb-6">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                <p class="text-base text-gray-700 mb-6 leading-relaxed">{{ $product->description }}</p>

                @if($product->stock > 0)
                    <p class="text-sm font-semibold text-green-600 uppercase tracking-widest mb-4">
                        Stok Tersedia: {{ $product->stock }}
                    </p>
                @else
                    <p class="text-sm font-semibold text-red-600 uppercase tracking-widest mb-4">
                        Stok Kosong
                    </p>
                @endif

                {{-- <button class="mt-6 px-10 py-3 border border-black rounded-full text-sm uppercase font-semibold tracking-widest text-black hover:bg-black hover:text-white transition duration-300">
                    Tambah ke Keranjang
                </button> --}}
            </div>
        </div>
    </main>

    <!-- Footer sederhana untuk konsistensi -->
    <footer class="bg-black text-white mt-20">
        <div class="container mx-auto px-6 py-8 text-center text-xs uppercase tracking-widest">
            <span>© Watch Store {{ date('Y') }}</span><br>
            <span>Powered by <a href="https://laravel.com" class="hover:underline">Laravel</a></span>
        </div>
    </footer>

</body>
</html>