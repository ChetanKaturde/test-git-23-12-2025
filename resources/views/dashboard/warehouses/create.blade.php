@extends('layouts.app')

@section('title', 'Add New Warehouse')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Warehouse</h1>
        <a href="{{ route('dashboard.warehouses.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Warehouse Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('dashboard.warehouses.store') }}">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Warehouse Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
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
                <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
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
                   <div class="row">
    <div class="col-md-6">
        <!-- Capacity -->
        <div class="form-group">
            <label for="capacity">Capacity <span class="text-danger">*</span></label>
            <input type="number" min="0" class="form-control @error('capacity') is-invalid @enderror" 
                   id="capacity" name="capacity" value="{{ old('capacity') }}" required>
            @error('capacity')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
       </div>

        <!-- Address -->
        <div class="form-group">
            <label for="address">Address <span class="text-danger">*</span></label>
            <textarea class="form-control @error('address') is-invalid @enderror" 
                      id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>


   <div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="state">State <span class="text-danger">*</span></label>
       <!-- To this: -->
<select class="form-control @error('state') is-invalid @enderror"
        id="state" name="state" required>
    <option value="">Select State</option>
    @foreach($states as $code => $label)
        <option value="{{ $label }}" {{ old('state') == $label ? 'selected' : '' }}>
            {{ $label }}
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
                                           id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}">
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_email">Contact Email</label>
                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                           id="contact_email" name="contact_email" value="{{ old('contact_email') }}">
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
                                    <label for="is_active">Status <span class="text-danger">*</span></label>
<select name="is_active" id="is_active" class="form-control" required>
    <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Active</option>
    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
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
                                               {{ old('is_default') ? 'checked' : '' }}>
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
                                <i class="fas fa-save"></i> Create Warehouse
                            </button>
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
                    <h6 class="m-0 font-weight-bold text-primary">Instructions</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success"></i> Fill in all required fields marked with <span class="text-danger">*</span></li>
                        <li><i class="fas fa-check text-success"></i> Choose appropriate warehouse type</li>
                        <li><i class="fas fa-check text-success"></i> Provide complete address information</li>
                        <li><i class="fas fa-check text-success"></i> Contact information is optional but recommended</li>
                        <li><i class="fas fa-check text-success"></i> Only one warehouse can be set as default</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const oldCity = @json(old('city', ''));
    const stateSelect = document.getElementById('state');
    const citySelect = document.getElementById('city');

    async function populateCities(selectedState, selectedCity = null) {
        citySelect.innerHTML = '<option value="">Loading...</option>';

        if (!selectedState) {
            citySelect.innerHTML = '<option value="">Select City/District</option>';
            citySelect.disabled = true;
            return;
        }

        try {
            const response = await fetch(`/api/cities/${encodeURIComponent(selectedState)}`);
            const cities = await response.json();
            
            citySelect.innerHTML = '<option value="">Select City/District</option>';
            citySelect.disabled = false;
            
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
            citySelect.disabled = true;
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
        citySelect.disabled = true;
        
        // Load cities if state is already selected (for validation errors)
        if (stateSelect.value) {
            populateCities(stateSelect.value, oldCity);
        }
    });

    // Change cities when user selects a different state
    stateSelect.addEventListener('change', function() {
        populateCities(this.value);
    });
</script>
@endsection