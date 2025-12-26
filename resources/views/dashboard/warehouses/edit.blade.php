@extends('layouts.app')

@section('title', 'Edit Warehouse')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Warehouse: {{ $warehouse->name }}</h1>
        <div>
            <a href="{{ route('dashboard.warehouses.show', $warehouse) }}" class="btn btn-info">
                <i class="fas fa-eye"></i> View Details
            </a>
            <a href="{{ route('dashboard.warehouses.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Update Warehouse Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('dashboard.warehouses.update', $warehouse) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Warehouse Name <span class="text-danger">*</span></label>
                                   <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $warehouse->name) }}" required >
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Warehouse Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror" 
                                            id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        @foreach($types as $key => $label)
                                            <option value="{{ $key }}" 
                                                    {{ old('type', $warehouse->type) == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                      
<div class="col-md-6">
    <!-- Capacity -->
    <div class="form-group">
        <label for="capacity">Capacity <span class="text-danger">*</span></label>
        <input type="number" min="0" 
               class="form-control @error('capacity') is-invalid @enderror" 
               id="capacity" name="capacity" 
               value="{{ old('capacity', $warehouse->capacity) }}" required>
        @error('capacity')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>


                        <!-- Location Information -->
                        <div class="form-group">
                            <label for="address">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="2" required>{{ old('address', $warehouse->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                     <div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="state">State <span class="text-danger">*</span></label>
            <select class="form-control @error('state') is-invalid @enderror" 
                    id="state" name="state" required>
                <option value="">Select State</option>
                @foreach($states as $stateName)
                    <option value="{{ $stateName }}" 
                        {{ old('state', $warehouse->state) == $stateName ? 'selected' : '' }}>
                        {{ $stateName }}
                    </option>
                @endforeach
            </select>
            @error('state')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="city">City/District <span class="text-danger">*</span></label>
            <select class="form-control @error('city') is-invalid @enderror" 
                    id="city" name="city" required>
                <option value="">Select City/District</option>
                {{-- Cities will be loaded by JavaScript --}}
            </select>
            @error('city')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

                        <!-- Contact Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_phone">Contact Phone</label>
                                    <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" 
                                           id="contact_phone" name="contact_phone" 
                                           value="{{ old('contact_phone', $warehouse->contact_phone) }}">
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_email">Contact Email</label>
                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                           id="contact_email" name="contact_email" 
                                           value="{{ old('contact_email', $warehouse->contact_email) }}">
                                    @error('contact_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Status and Options -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="active" 
                                                {{ old('status', $warehouse->is_active ? 'active' : 'inactive') == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="inactive" 
                                                {{ old('status', $warehouse->is_active ? 'active' : 'inactive') == 'inactive' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox mt-4">
                                        <input type="checkbox" class="custom-control-input" 
                                               id="is_default" name="is_default" value="1" 
                                               {{ old('is_default', $warehouse->is_default) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_default">
                                            Set as Default Warehouse
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Only one warehouse can be set as default at a time.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Warehouse
                            </button>
                            <a href="{{ route('dashboard.warehouses.show', $warehouse) }}" class="btn btn-info">
                                View Details
                            </a>
                            <a href="{{ route('dashboard.warehouses.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Warehouse Info</h6>
                </div>
                <div class="card-body">
                    <p><strong>Created:</strong> {{ $warehouse->created_at->format('M d, Y') }}</p>
                    <p><strong>Last Updated:</strong> {{ $warehouse->updated_at->format('M d, Y') }}</p>
                    <p><strong>Assigned Staff:</strong> {{ $warehouse->users->count() }}</p>
                    <p><strong>Current Status:</strong> 
                        <span class="badge badge-{{ $warehouse->is_active ? 'success' : 'secondary' }}">
                            {{ $warehouse->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                    @if($warehouse->is_default)
                        <p><strong>Default:</strong> 
                            <span class="badge badge-warning">
                                <i class="fas fa-star"></i> Yes
                            </span>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const oldState = @json(old('state', $warehouse->state));
    const oldCity = @json(old('city', $warehouse->city));

    const stateSelect = document.getElementById('state');
    const citySelect = document.getElementById('city');

    async function populateCities(selectedState, selectedCity = null) {
        citySelect.innerHTML = '<option value="">Loading...</option>';

        if (!selectedState) {
            citySelect.innerHTML = '<option value="">Select City/District</option>';
            return;
        }

        try {
            const response = await fetch(`/api/cities/${encodeURIComponent(selectedState)}`);
            const cities = await response.json();
            
            citySelect.innerHTML = '<option value="">Select City/District</option>';
            
            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.textContent = city;

                if (city === selectedCity) {
                    option.selected = true;
                }

                citySelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error fetching cities:', error);
            citySelect.innerHTML = '<option value="">Error loading cities</option>';
        }
    }

    // Populate cities when the page loads
    document.addEventListener('DOMContentLoaded', () => {
        if (oldState) {
            populateCities(oldState, oldCity);
        }
    });

    // Change cities when user selects a different state
    stateSelect.addEventListener('change', function() {
        populateCities(this.value);
    });
</script>


@endsection