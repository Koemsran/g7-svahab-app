<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.orders.store') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label for="product" class="block mb-2 font-medium text-gray-900">Product</label>
                            <select id="product" name="product_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">Select a product</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label for="quantity" class="block mb-2 font-medium text-gray-900">Quantity</label>
                            <input type="number" id="quantity" name="qty" min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>

                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>