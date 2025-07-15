<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WATCH STORE - Luxury Timepieces</title>
    @vite('resources/css/app.css')

    <!-- Alpine.js untuk Dropdown -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-white font-sans" x-data="{ openUserMenu: false }">

    <!-- Hero Section -->
    <header class="relative h-screen bg-cover bg-center overflow-hidden"
        style="background-image: url('{{ asset('images/hero-watch.jpg') }}')">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="absolute top-0 left-0 w-full px-8 py-6 z-20">
            <div class="container mx-auto flex justify-between items-center">
                <a href="/" class="text-xl font-bold tracking-wider text-black">WATCH STORE</a>
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#"
                        class="text-sm font-semibold uppercase tracking-widest text-black hover:text-gray-600">Shop</a>
                    <a href="#"
                        class="text-sm font-semibold uppercase tracking-widest text-black hover:text-gray-600">About</a>
                    <a href="#"
                        class="text-sm font-semibold uppercase tracking-widest text-black hover:text-gray-600">Contact</a>
                </nav>
                <div class="flex items-center space-x-6">
                    {{-- Cek apakah customer sudah login --}}
                    @if(auth()->guard('customer')->check())
                        <!-- Dropdown User -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="text-sm font-semibold uppercase tracking-widest text-black hover:text-gray-600 focus:outline-none">
                                {{ Auth::guard('customer')->user()->name }}
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 shadow-lg rounded-md z-50">
                                <form method="POST" action="{{ route('customer.logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-800 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Jika belum login -->
                        <a class="btn btn-outline-primary text-sm font-semibold uppercase tracking-widest text-black hover:text-gray-600"
                            href="{{ route('customer.login') }}">Login</a>
                        <a class="btn btn-primary text-sm font-semibold uppercase tracking-widest text-black hover:text-gray-600"
                            href="{{ route('customer.register') }}">Register</a>
                    @endif

                </div>
            </div>
        </div>

        <!-- Hero Text -->
        <div class="absolute bottom-20 left-10 md:left-20 text-black z-10">
            <div class="flex flex-col items-start">
                <div>
                    <h2 class="font-serif text-5xl md:text-7xl font-bold">WATCH STORE</h2>
                    <h1 class="font-serif text-6xl md:text-8xl font-bold tracking-tight text-orange-400">RESPECT YOUR
                        TIME</h1>
                </div>
                <a href="#"
                    class="mt-8 px-10 py-3 border border-black rounded-full text-sm uppercase font-semibold tracking-widest text-black hover:bg-black hover:text-white transition-colors duration-300">Shop
                    Now</a>
            </div>
        </div>

        <!-- Running Banner -->
        <div class="absolute bottom-0 left-0 w-full bg-orange-400 py-3 z-10">
            <div class="relative flex overflow-x-hidden">
                <div class="flex flex-shrink-0 items-center animate-marquee">
                    <span class="mx-8 text-sm uppercase font-semibold tracking-wider text-black">100 Bonus Points on
                        Purchases Over $300</span>
                    <span class="mx-8 text-sm uppercase font-semibold tracking-wider text-black">SiteWide Sale / The
                        2024 Collection</span>
                    <span class="mx-8 text-sm uppercase font-semibold tracking-wider text-black">Up to 30% Off Selected
                        Items</span>
                </div>
            </div>
        </div>
    </header>

    <main>
        <!-- Section: Our Product (Styled like Hero theme) -->
        <section class="py-24 bg-white">
            <div class="container mx-auto px-6 md:px-8">
                <!-- Judul -->
                <h2 class="font-serif text-5xl md:text-6xl uppercase tracking-wider mb-12 text-gray-900">Our Product
                </h2>

                <!-- Row Kategori -->
                <div class="flex flex-wrap gap-4 mb-12">
                    <a href="{{ route('home') }}"
                        class="uppercase text-sm font-semibold tracking-widest px-5 py-2 rounded-full border border-black transition duration-300
               {{ request()->routeIs('home') ? 'bg-black text-white' : 'text-black hover:bg-black hover:text-white' }}">
                        All Category
                    </a>

                    @foreach($categories as $category)
                        <a href="{{ route('category.filter', $category->id) }}"
                            class="uppercase text-sm font-semibold tracking-widest px-5 py-2 rounded-full border border-black transition duration-300
                                       {{ request()->routeIs('category.filter') && request()->route('id') == $category->id ? 'bg-black text-white' : 'text-black hover:bg-black hover:text-white' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>

                <!-- Grid Produk -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                    @forelse($products as $product)
                        <a href="#" class="group block transition duration-300">
                            <div class="bg-gray-100 aspect-square flex items-center justify-center p-6">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-contain transition-transform duration-300 group-hover:scale-105">
                            </div>
                            <p class="mt-5 text-base text-gray-800 font-serif uppercase tracking-wide">{{ $product->name }}
                            </p>
                            <p class="text-sm text-gray-500 font-sans">Rp{{ number_format($product->price, 0, ',', '.') }}
                            </p>
                        </a>
                    @empty
                        <p class="text-gray-500 col-span-4">Tidak ada produk dalam kategori ini.</p>
                    @endforelse
                </div>
            </div>
        </section>



        <!-- Section Collection Highlight -->
        <section class="pb-20">
            <div class="container mx-auto px-6 md:px-8">
                <div class="flex flex-col lg:flex-row bg-stone-50">
                    <div class="lg:w-1/2"><img src="{{ asset('images/collection-banner.jpg') }}"
                            alt="Man wearing a luxury watch" class="w-full h-full object-cover"></div>
                    <div class="lg:w-1/2 flex flex-col justify-between p-12 md:p-20">
                        <div>
                            <p class="text-sm uppercase tracking-widest text-gray-500">The Dress Watch Collection</p>
                            <h3 class="font-serif text-5xl md:text-6xl my-4">ELEGANCE REDEFINED</h3>
                            <a href="#"
                                class="inline-block mt-4 px-10 py-3 border border-black rounded-full text-sm uppercase font-semibold tracking-widest text-black hover:bg-black hover:text-white transition-colors duration-300">Shop
                                All</a>
                        </div>
                        <div class="flex justify-end pt-12">
                            <div class="w-2/3 md:w-1/2"><a href="#" class="group">
                                    <div class="bg-gray-100 aspect-square"><img src="{{ asset('images/watch-5.png') }}"
                                            alt="Elegance Redefined Watch"
                                            class="w-full h-full object-contain p-4 transition-transform duration-300 group-hover:scale-105">
                                    </div>
                                </a></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section About Us -->
        <section class="py-24">
            <div class="container mx-auto px-6 text-center">
                <h2 class="font-serif text-3xl md:text-4xl uppercase tracking-wider leading-relaxed text-gray-800">
                    About Us
                </h2>
                <h2 class="font-serif text-3xl md:text-4xl uppercase tracking-wider leading-relaxed text-gray-800">
                    Precision in Every Tick. Timeless Elegance, Curated for the Modern Gentleman.
                </h2>
            </div>
        </section>

        <!-- Section Follow Us -->
        <section class="pb-20">
            <div class="container mx-auto px-6 md:px-8">
                <div class="flex justify-between items-baseline mb-8">
                    <h3 class="font-serif text-3xl md:text-4xl uppercase tracking-wider">Follow Us</h3><a href="#"
                        class="font-serif text-2xl md:text-3xl uppercase tracking-wider text-gray-800 hover:text-black">@watchstore.official</a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4"><a href="#" class="block group"><img
                            src="{{ asset('images/insta-1.jpg') }}" alt="Instagram post 1"
                            class="aspect-[4/3] w-full object-cover transition-opacity group-hover:opacity-80"></a><a
                        href="#" class="block group"><img src="{{ asset('images/insta-2.jpg') }}" alt="Instagram post 2"
                            class="aspect-[4/3] w-full object-cover transition-opacity group-hover:opacity-80"></a><a
                        href="#" class="block group"><img src="{{ asset('images/insta-3.jpg') }}" alt="Instagram post 3"
                            class="aspect-[4/3] w-full object-cover transition-opacity group-hover:opacity-80"></a><a
                        href="#" class="block group"><img src="{{ asset('images/insta-4.jpg') }}" alt="Instagram post 4"
                            class="aspect-[4/3] w-full object-cover transition-opacity group-hover:opacity-80"></a>
                </div>
            </div>
        </section>

        <!-- Section Newsletter Sign Up -->
        <section class="bg-orange-300 text-black py-20">
            <div class="container mx-auto px-6 text-center">
                <!-- <p class="text-sm font-semibold uppercase tracking-widest mb-2">Subscribe to our</p> -->
                <h2 class="font-serif text-6xl md:text-8xl uppercase tracking-wider mb-4">watch store</h2>
                <!-- <p class="max-w-md mx-auto text-xs uppercase tracking-wider mb-8">Sign up with your email to receive
                    news about new collections, events and sales.</p>
                <form action="#" method="POST" class="flex flex-col items-center space-y-6">
                    <input type="email" name="email" placeholder="Email Address" required
                        class="w-full max-w-sm px-4 py-3 bg-white border-0 text-center placeholder-gray-500 focus:ring-2 focus:ring-black">
                    <button type="submit"
                        class="px-12 py-3 border border-black rounded-full text-sm uppercase font-semibold tracking-widest text-black hover:bg-black hover:text-white transition-colors duration-300">Sign
                        Up</button>
                </form> -->
            </div>
        </section>
    </main>

    <!-- =================================================================== -->
    <!--                       BAGIAN BARU: FOOTER                           -->
    <!-- =================================================================== -->
    <footer>

        <!-- Area Tautan Footer -->
        <div class="bg-black text-white">
            <div class="container mx-auto px-6 md:px-8 py-12">
                <div class="text-xs uppercase tracking-widest text-center mx-auto">
                    <span>© WATCH STORE {{ date('Y') }}</span><br>
                    <span>Powered by <a href="https://laravel.com" class="hover:underline">Laravel</a></span>
                </div>
            </div>
        </div>

    </footer>

</body>

</html>