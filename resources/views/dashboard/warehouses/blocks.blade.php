@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Warehouse: {{ $warehouse->name }}</h2>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Block Creation Form --}}
    <div class="card mb-4">
        <div class="card-header">Add New Block</div>
        <div class="card-body">
            <form method="POST" action="{{ route('warehouses.blocks.store', $warehouse->id) }}">
                @csrf
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <input type="text" name="name" class="form-control" placeholder="Block Name" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="number" name="rows" class="form-control" placeholder="Rows" required min="1">
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="number" name="columns" class="form-control" placeholder="Columns" required min="1">
                    </div>
                    <div class="col-md-2 mb-2">
                        <button class="btn btn-primary w-100">Create Block</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Existing Blocks and Slots --}}
    @foreach($blocks as $block)
        <div class="card mb-5">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Block: {{ $block->name }} ({{ $block->rows }} × {{ $block->columns }})</h5>
            </div>
            <div class="card-body p-2">
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <tbody>
                            @for($r = 1; $r <= $block->rows; $r++)
                                <tr>
                                    @for($c = 1; $c <= $block->columns; $c++)
                                        @php
                                            $slot = $block->slots->firstWhere(fn($s) => $s->row == $r && $s->column == $c);
                                            $status = $slot?->status ?? 'empty';

                                            $colorClass = match($status) {
                                                'empty' => 'bg-success text-white',
                                                'partial' => 'bg-warning text-dark',
                                                'full' => 'bg-danger text-white',
                                                default => 'bg-secondary text-white'
                                            };
                                        @endphp
                                        <td class="{{ $colorClass }}">
                                            R{{ $r }}-C{{ $c }}<br>
                                            <small>{{ strtoupper($status) }}</small>
                                        </td>
                                    @endfor
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach

</div>
@endsection
