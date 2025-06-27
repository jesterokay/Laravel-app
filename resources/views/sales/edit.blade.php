@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Sale</h1>
        <form action="{{ route('sales.update', $sale) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium">Customer</label>
                <select name="customer_id" class="mt-1 block w-full border rounded p-2">
                    <option value="">None</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" {{ $sale->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->first_name }} {{ $customer->last_name }}</option>
                    @endforeach
                </select>
                @error('customer_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Employee</label>
                <select name="user_id" class="mt-1 block w-full border rounded p-2">
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" {{ $sale->user_id == $employee->id ? 'selected' : '' }}>{{ $employee->first_name }} {{ $employee->last_name }}</option>
                    @endforeach
                </select>
                @error('user_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Status</label>
                <select name="status" class="mt-1 block w-full border rounded p-2">
                    <option value="completed" {{ $sale->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="pending" {{ $sale->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="cancelled" {{ $sale->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <h2 class="text-lg font-semibold">Sale Items</h2>
                <div id="items">
                    @if ($sale->saleItems->isEmpty())
                        <p>No sale items found for this sale. Add a new item below.</p>
                    @else
                        @foreach ($sale->saleItems as $index => $item)
                            <div class="item mb-4 border p-4 rounded">
                                <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                <label class="block text-sm font-medium">Product</label>
                                <select name="items[{{ $index }}][product_id]" class="mt-1 block w-full border rounded p-2">
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }} ({{ $product->price }})</option>
                                    @endforeach
                                </select>
                                @error("items.$index.product_id") <span class="text-red-500">{{ $message }}</span> @enderror
                                <label class="block text-sm font-medium mt-2">Quantity</label>
                                <input type="number" name="items[{{ $index }}][quantity]" min="1" class="mt-1 block w-full border rounded p-2" value="{{ $item->quantity }}">
                                @error("items.$index.quantity") <span class="text-red-500">{{ $message }}</span> @enderror
                                <label class="block text-sm font-medium mt-2">Tax Rate</label>
                                <select name="items[{{ $index }}][tax_rate_id]" class="mt-1 block w-full border rounded p-2">
                                    <option value="" {{ is_null($item->tax_rate_id) ? 'selected' : '' }}>None</option>
                                    @foreach ($taxRates as $taxRate)
                                        <option value="{{ $taxRate->id }}" {{ $item->tax_rate_id == $taxRate->id ? 'selected' : '' }}>{{ $taxRate->rate }}%</option>
                                    @endforeach
                                </select>
                                @error("items.$index.tax_rate_id") <span class="text-red-500">{{ $message }}</span> @enderror
                                <button type="button" class="remove-item bg-red-500 text-white px-2 py-1 rounded mt-2">Remove</button>
                            </div>
                        @endforeach
                    @endif
                </div>
                <button type="button" id="add-item" class="bg-gray-500 text-white px-4 py-2 rounded">Add Item</button>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
    <script>
        let itemIndex = {{ $sale->saleItems->count() }};
        document.getElementById('add-item').addEventListener('click', () => {
            const itemsDiv = document.getElementById('items');
            const newItem = document.createElement('div');
            newItem.classList.add('item', 'mb-4', 'border', 'p-4', 'rounded');
            newItem.innerHTML = `
                <label class="block text-sm font-medium">Product</label>
                <select name="items[${itemIndex}][product_id]" class="mt-1 block w-full border rounded p-2">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->price }})</option>
                    @endforeach
                </select>
                <label class="block text-sm font-medium mt-2">Quantity</label>
                <input type="number" name="items[${itemIndex}][quantity]" min="1" class="mt-1 block w-full border rounded p-2" value="1">
                <label class="block text-sm font-medium mt-2">Tax Rate</label>
                <select name="items[${itemIndex}][tax_rate_id]" class="mt-1 block w-full border rounded p-2">
                    <option value="">None</option>
                    @foreach ($taxRates as $taxRate)
                        <option value="{{ $taxRate->id }}">{{ $taxRate->rate }}%</option>
                    @endforeach
                </select>
                <button type="button" class="remove-item bg-red-500 text-white px-2 py-1 rounded mt-2">Remove</button>
            `;
            itemsDiv.appendChild(newItem);
            itemIndex++;
            newItem.querySelector('.remove-item').addEventListener('click', () => newItem.remove());
        });
        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', () => button.parentElement.remove());
        });
    </script>
@endsection