@extends('layouts.app')

@section('title', 'Add Warehouse Block')

@section('content')
<div class="container mt-4">
    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0 text-dark">Add Block to {{ $warehouse->name }}</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('warehouses.blocks.store', $warehouse->id) }}">
                @csrf
                <div class="form-group">
                    <label for="name">Block Name</label>
                    <input type="text" class="form-control" id="name" name="name" required placeholder="e.g. Block A">
                </div>

                <div class="form-row mt-3">
    <div class="form-group col-md-6">
        <label for="rows">Number of Rows</label>
        <input type="number" class="form-control" id="rows" name="rows" min="1" required>
    </div>
    <div class="form-group col-md-6">
        <label for="columns">Number of Columns</label>
        <input type="number" class="form-control" id="columns" name="columns" min="1" required>
    </div>
</div>


                <button type="submit" class="btn btn-success mt-3">Create Block</button>
                <a href="{{ route('warehouses.blocks.index', $warehouse->id) }}" class="btn btn-secondary mt-3">Back</a>
            </form>
        </div>
    </div>
</div>
@endsection
