<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Watch Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-white text-black font-serif min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white shadow-lg rounded-2xl p-8 border border-gray-200">
        <h2 class="text-3xl font-bold text-center mb-6 tracking-wider">Register</h2>

        @if (session('errorMessage'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('errorMessage') }}
            </div>
        @endif

        <form method="POST" action="{{ route('customer.store_register') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-semibold mb-1">Name</label>
                <input type="text" name="name" id="name"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black @error('name') border-red-500 @enderror"
                       value="{{ old('name') }}" required autofocus>
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold mb-1">Email Address</label>
                <input type="email" name="email" id="email"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black @error('email') border-red-500 @enderror"
                       value="{{ old('email') }}" required>
                @error('email')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold mb-1">Password</label>
                <input type="password" name="password" id="password"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black @error('password') border-red-500 @enderror"
                       required>
                @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black @error('password_confirmation') border-red-500 @enderror"
                       required>
                @error('password_confirmation')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full px-4 py-2 border border-black text-black rounded-full uppercase text-sm font-semibold tracking-widest hover:bg-black hover:text-white transition duration-300">
                Register
            </button>
        </form>

        <p class="text-center text-sm mt-4">
            Sudah punya akun?
            <a href="{{ route('customer.login') }}" class="underline hover:text-gray-700">Login di sini</a>
        </p>
    </div>

</body>
</html>
