@extends('layouts.app')

@section('title', 'Inventory Items')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Inventory Items</h2>

    <a href="{{ route('inventory.create') }}" class="btn btn-primary mb-3">Add New Item</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->category }}</td>
                    <td>
                        <a href="{{ route('inventory.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('inventory.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No inventory items found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
