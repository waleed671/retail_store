@extends('layouts.admin')

@section('title', 'Customers')
@section('page-title', 'Customers')

@section('content')
    <form action="{{ route('admin.customers.index') }}" method="GET" class="mb-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name or email..." class="border rounded px-3 py-2 text-sm w-64">
    </form>

    <div class="bg-white border rounded-lg overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-xs text-gray-500 border-b bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-2">Name</th>
                    <th class="text-left px-4 py-2">Email</th>
                    <th class="text-left px-4 py-2">Phone</th>
                    <th class="text-left px-4 py-2">City</th>
                    <th class="text-right px-4 py-2">Orders</th>
                    <th class="text-left px-4 py-2">Joined</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($customers as $customer)
                    <tr>
                        <td class="px-4 py-2">{{ $customer->name }}</td>
                        <td class="px-4 py-2">{{ $customer->email }}</td>
                        <td class="px-4 py-2">{{ $customer->phone ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $customer->city ?? '—' }}</td>
                        <td class="px-4 py-2 text-right">{{ $customer->orders_count }}</td>
                        <td class="px-4 py-2 text-gray-500">{{ $customer->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">No customers yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $customers->links() }}</div>
@endsection
