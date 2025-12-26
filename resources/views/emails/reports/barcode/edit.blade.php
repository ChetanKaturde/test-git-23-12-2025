@extends('layouts.app')

@section('title', 'Edit Barcode')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="h3 mb-0">✏️ Edit Barcode</h2>
                <div class="btn-group">
                    <a href="{{ route('barcode.show', $barcode) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Details
                    </a>
                    <a href="{{ route('barcode.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('barcode.update', $barcode) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Main Information -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-barcode me-2"></i> Barcode Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Barcode Number - Display Only -->
                                <div class="mb-3">
                                    <label for="barcode_number_display" class="form-label">Barcode Number</label>
                                    <input type="text" class="form-control" 
                                           id="barcode_number_display" 
                                           value="{{ $barcode->barcode_number }}" 
                                           readonly disabled>
                                    <small class="text-muted">
                                        <i class="fas fa-lock me-1"></i>Barcode number cannot be changed
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <label for="material_name" class="form-label">Material Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('material_name') is-invalid @enderror"
                                           id="material_name" name="material_name" 
                                           value="{{ old('material_name', $barcode->material_name) }}" required>
                                    @error('material_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="material_code" class="form-label">Material Code</label>
                                    <input type="text" class="form-control @error('material_code') is-invalid @enderror"
                                           id="material_code" name="material_code" 
                                           value="{{ old('material_code', $barcode->material_code) }}">
                                    @error('material_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="batch_id" class="form-label">Batch</label>
                                    <select class="form-select @error('batch_id') is-invalid @enderror" id="batch_id" name="batch_id">
                                        <option value="">Select Batch</option>
                                        @foreach($batches as $batch)
                                            <option value="{{ $batch->id }}" 
                                                {{ old('batch_id', $barcode->batch_id) == $batch->id ? 'selected' : '' }}>
                                                {{ $batch->batch_number }} - {{ $batch->material_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('batch_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="storage_location" class="form-label">Storage Location</label>
                                    <input type="text" class="form-control @error('storage_location') is-invalid @enderror"
                                           id="storage_location" name="storage_location" 
                                           value="{{ old('storage_location', $barcode->storage_location) }}">
                                    @error('storage_location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                           id="quantity" name="quantity" 
                                           value="{{ old('quantity', $barcode->quantity) }}" 
                                           min="0" step="0.01" required>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="weight" class="form-label">Weight (kg)</label>
                                    <input type="number" class="form-control @error('weight') is-invalid @enderror"
                                           id="weight" name="weight" 
                                           value="{{ old('weight', $barcode->weight) }}" 
                                           min="0" step="0.01">
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="unit_price" class="form-label">Unit Price (₹)</label>
                                    <input type="number" class="form-control @error('unit_price') is-invalid @enderror"
                                           id="unit_price" name="unit_price" 
                                           value="{{ old('unit_price', $barcode->unit_price) }}" 
                                           min="0" step="0.01">
                                    @error('unit_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="quality_grade" class="form-label">Quality Grade</label>
                                    <select class="form-select @error('quality_grade') is-invalid @enderror" 
                                            id="quality_grade" name="quality_grade">
                                        <option value="">Select Grade</option>
                                        <option value="A" {{ old('quality_grade', $barcode->quality_grade) == 'A' ? 'selected' : '' }}>Grade A</option>
                                        <option value="B" {{ old('quality_grade', $barcode->quality_grade) == 'B' ? 'selected' : '' }}>Grade B</option>
                                        <option value="C" {{ old('quality_grade', $barcode->quality_grade) == 'C' ? 'selected' : '' }}>Grade C</option>
                                        <option value="Premium" {{ old('quality_grade', $barcode->quality_grade) == 'Premium' ? 'selected' : '' }}>Premium</option>
                                        <option value="Standard" {{ old('quality_grade', $barcode->quality_grade) == 'Standard' ? 'selected' : '' }}>Standard</option>
                                    </select>
                                    @error('quality_grade')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="expiry_date" class="form-label">Expiry Date</label>
                                    <input type="date" class="form-control @error('expiry_date') is-invalid @enderror"
                                           id="expiry_date" name="expiry_date" 
                                           value="{{ old('expiry_date', $barcode->expiry_date ? $barcode->expiry_date->format('Y-m-d') : '') }}">
                                    @error('expiry_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="active" {{ old('status', $barcode->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $barcode->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="damaged" {{ old('status', $barcode->status) == 'damaged' ? 'selected' : '' }}>Damaged</option>
                                        <option value="expired" {{ old('status', $barcode->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes', $barcode->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barcode Settings - Read Only -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-cogs me-2"></i> Barcode Settings
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Barcode Type - Display Only -->
                        <div class="mb-3">
                            <label for="barcode_type_display" class="form-label">Barcode Type</label>
                            <input type="text" class="form-control" 
                                   id="barcode_type_display" 
                                   value="{{ ucfirst($barcode->barcode_type) }}" 
                                   readonly disabled>
                            <small class="text-muted">
                                <i class="fas fa-lock me-1"></i>Barcode type cannot be changed
                            </small>
                        </div>

                        <!-- QR Code Data - Display Only -->
                        <div class="mb-3">
                            <label for="qr_code_data_display" class="form-label">QR Code Data</label>
                            <textarea class="form-control" 
                                      id="qr_code_data_display" 
                                      rows="3" readonly disabled>{{ $barcode->qr_code_data }}</textarea>
                            <small class="text-muted">
                                <i class="fas fa-lock me-1"></i>QR code data cannot be changed
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Current Barcode Preview -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-eye me-2"></i> Current Preview
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        @if(in_array($barcode->barcode_type, ['standard', 'both']))
                            <div class="mb-3">
                                <h6>Standard Barcode</h6>
                                <div class="p-2 bg-light rounded">
                                    <img src="{{ route('barcode.generate-barcode', ['number' => $barcode->barcode_number]) }}" 
                                         alt="Current Barcode" class="img-fluid" style="max-height: 60px;">
                                </div>
                            </div>
                        @endif
                        
                        @if(in_array($barcode->barcode_type, ['qr', 'both']))
                            <div class="mb-3">
                                <h6>QR Code</h6>
                                <div class="p-2 bg-light rounded">
                                    <img src="{{ route('barcode.generate-qr', ['data' => urlencode($barcode->qr_code_data)]) }}" 
                                         alt="Current QR Code" class="img-fluid" style="max-width: 100px;">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="{{ route('barcode.show', $barcode) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Barcode
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Note: QR code data auto-generation is removed since it's read-only in edit mode
    document.addEventListener('DOMContentLoaded', function() {
        // Add any additional JavaScript if needed for the edit form
        console.log('Barcode edit form loaded');
    });
</script>
@endpush