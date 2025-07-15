<x-layouts.app :title="'Kategori Produk'">
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-7">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Daftar order</h2>
            
        </div>

        <table class="min-w-full bg-white shadow-md rounded-lg">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-b">No. Order</th>
                    <th class="px-4 py-2 border-b">Total</th>
                    <th class="px-4 py-2 border-b">Alamat</th>
                    <th class="px-4 py-2 border-b">Item</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr class="hover:bg-gray-100">
                    <td class="px-4 py-2 border-b">{{ $order->order_number }}</td>
                    <td class="px-4 py-2 border-b">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 border-b">
                        {{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}
                    </td>
                    <td class="px-4 py-2 border-b">
                        <ul class="list-disc list-inside">
                            @foreach ($order->items as $item)
                            <li>Produk ID: {{ $item->product_id }} ({{ $item->quantity }} x Rp{{ number_format($item->price, 0, ',', '.') }})</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</x-layouts.app>