@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Pending Material Requests</h1>

    @if($requests->isEmpty())
        <div class="alert alert-info">
            No pending material requests found.
        </div>
    @else
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Material Name</th>
                    <th>Requested By</th>
                    <th>Requested At</th>
                    <th>Create Material</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($requests as $index => $request)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $request->name }}</td>
                        <td>{{ $request->requestedBy->name ?? 'Unknown' }}</td>
                        <td>{{ $request->created_at->format('d M Y, h:i A') }}</td>
                        <td>
                            <a href="{{ route('materials.create', ['name' => $request->name]) }}"
                               class="btn btn-sm btn-success">
                                Create
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
