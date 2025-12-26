@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">All Warehouses - Blocks & Slots</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @foreach($warehouses as $warehouse)
        <div class="card mb-5">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">{{ $warehouse->name }}</h4>
                <a href="{{ route('warehouses.blocks.create', $warehouse->id) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Add Block
                </a>
            </div>

            <div class="card-body">
                @if($warehouse->blocks->isEmpty())
                    <div class="alert alert-info">No blocks found for this warehouse.</div>
                @else
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Name</th>
                                <th>Dimensions</th>
                                <th>Total Slots</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($warehouse->blocks as $block)
                                <tr>
                                    <td>{{ $block->name }}</td>
                                    <td>{{ $block->rows }} × {{ $block->columns }}</td>
                                    <td>
                                        <span class="badge badge-info text-dark">
                                            {{ $block->slots->count() }} slots
                                        </span>
                                    </td>
                                   <td>
    <div class="btn-group btn-group-sm" role="group">
        <a href="{{ route('warehouses.blocks.show', [$warehouse->id, $block->id]) }}" class="btn btn-info">
            <i class="fas fa-eye"></i>
        </a>

        <a href="{{ route('warehouses.blocks.edit', [$warehouse->id, $block->id]) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i>
        </a>

        <form action="{{ route('warehouses.blocks.destroy', [$warehouse->id, $block->id]) }}"
              method="POST"
              style="display:inline;"
              onsubmit="return confirm('Are you sure you want to delete this block and its slots?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash"></i> 
            </button>
        </form>
    </div>
</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
