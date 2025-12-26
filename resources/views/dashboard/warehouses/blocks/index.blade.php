@extends('layouts.app')

@section('content')

       <div class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">All Warehouses - Blocks & Slots</h2>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @foreach($warehouses as $warehouse)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ $warehouse->name }}</h4>
                        <a href="{{ route('warehouses.blocks.create', $warehouse->id) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus"></i> Add Block
                        </a>
                    </div>

                    <div class="card-body p-0">
                        @if($warehouse->blocks->isEmpty())
                            <div class="alert alert-info m-3">
                                <i class="fas fa-info-circle"></i> No blocks found for this warehouse.
                            </div>
                        @else
                            {{-- Desktop Table View --}}
                            <div class="d-none d-lg-block">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Name</th>
                                                <th>Dimensions</th>
                                                <th>Total Slots</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($warehouse->blocks as $block)
                                                <tr>
                                                    <td class="fw-bold">{{ $block->name }}</td>
                                                    <td>{{ $block->rows }} × {{ $block->columns }}</td>
                                                    <td>
                                                        <span class="badge bg-info text-dark">
                                                            {{ $block->slots->count() }} slots
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group btn-group-sm">
                                                            @canAccess('view', 'blocks')
                                                                <a href="{{ route('warehouses.blocks.show', [$warehouse->id, $block->id]) }}"
                                                                   class="btn btn-outline-info" title="View">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            @endcanAccess
                                                            @canAccess('edit', 'blocks')
                                                                <a href="{{ route('warehouses.blocks.edit', [$warehouse->id, $block->id]) }}"
                                                                   class="btn btn-outline-warning" title="Edit">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            @endcanAccess
                                                            @canAccess('delete', 'blocks')
                                                                <form action="{{ route('warehouses.blocks.destroy', [$warehouse->id, $block->id]) }}"
                                                                      method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-outline-danger"
                                                                            onclick="return confirm('Delete this block?')" title="Delete">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endcanAccess
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Mobile Card View --}}
                            <div class="d-lg-none">
                                @foreach($warehouse->blocks as $block)
                                    <div class="border-bottom px-3 py-3">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="fw-bold mb-1">{{ $block->name }}</h6>
                                                <div class="text-muted small mb-2">
                                                    {{ $block->rows }} × {{ $block->columns }} dimensions
                                                </div>
                                                <span class="badge bg-info text-dark">
                                                    {{ $block->slots->count() }} slots
                                                </span>
                                            </div>
                                            <div class="text-end">
                                                @canAccess('view', 'blocks')
                                                    <a href="{{ route('warehouses.blocks.show', [$warehouse->id, $block->id]) }}"
                                                       class="btn btn-outline-info btn-sm mb-1">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endcanAccess
                                                @canAccess('edit', 'blocks')
                                                    <a href="{{ route('warehouses.blocks.edit', [$warehouse->id, $block->id]) }}"
                                                       class="btn btn-outline-warning btn-sm mb-1">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcanAccess
                                                @canAccess('delete', 'blocks')
                                                    <form action="{{ route('warehouses.blocks.destroy', [$warehouse->id, $block->id]) }}"
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                                onclick="return confirm('Delete this block?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcanAccess
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>


@endsection