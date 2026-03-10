<x-default-layout>

    @section('title')
        Create Site Content
    @endsection

    @section('breadcrumbs')
        <ul class="breadcrumb fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">
                <a href="{{ route('admin.site-content.index') }}" class="text-muted text-hover-primary">Content Management</a>
            </li>
            <li class="breadcrumb-item text-dark">Create Content</li>
        </ul>
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.site-content.index') }}" class="btn btn-sm btn-light-primary me-3">
                    {!! getIcon('arrow-left', 'fs-3') !!}
                    Back
                </a>
                <h3 class="card-title">Create New Content Section</h3>
            </div>
        </div>

        <form action="{{ route('admin.site-content.store') }}" method="POST" id="contentForm" enctype="multipart/form-data">
            @csrf

            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Section Dropdown -->
                <div class="mb-10">
                    <label class="form-label required">Section Type</label>
                    <select name="section" 
                            id="sectionSelect"
                            class="form-select form-select-solid @error('section') is-invalid @enderror" 
                            required>
                        <option value="">Select Section</option>
                        @foreach($availableSections as $key => $label)
                            <option value="{{ $key }}" {{ old('section') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('section')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Choose the type of content section you want to create</div>
                </div>

                <!-- Dynamic Fields Container -->
                <div id="dynamicFields">
                    <!-- Title Field (shown for all) -->
                    <div class="mb-10 field-group" data-sections="hero,info,footer_about,footer_contact,custom">
                        <label class="form-label required">Title</label>
                        <input type="text" 
                               name="title" 
                               class="form-control form-control-solid @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}"
                               placeholder="Enter title">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Subtitle Field (Hero only) -->
                    <div class="mb-10 field-group" data-sections="hero" style="display: none;">
                        <label class="form-label">Subtitle</label>
                        <textarea name="subtitle" 
                                  class="form-control form-control-solid @error('subtitle') is-invalid @enderror" 
                                  rows="3"
                                  placeholder="Enter subtitle">{{ old('subtitle') }}</textarea>
                        @error('subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Subtitle text displayed below the hero title</div>
                    </div>

                    <!-- Hero Background Image (Hero only) -->
                    <div class="mb-10 field-group" data-sections="hero" style="display: none;">
                        <label class="form-label">Background Image</label>
                        <input type="file" 
                               name="image" 
                               class="form-control @error('image') is-invalid @enderror"
                               accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Upload hero section background image (Max 5MB, JPG/PNG/GIF)</div>
                    </div>

                    <!-- Content Field -->
                    <div class="mb-10 field-group" data-sections="info,footer_about,custom" style="display: none;">
                        <label class="form-label">Content</label>
                        <textarea name="content" 
                                  class="form-control form-control-solid @error('content') is-invalid @enderror" 
                                  rows="5"
                                  placeholder="Enter content">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Contact Fields (Footer Contact only) -->
                    <div class="field-group" data-sections="footer_contact" style="display: none;">
                        <div class="mb-10">
                            <label class="form-label">Email</label>
                            <input type="email" 
                                   name="email" 
                                   class="form-control form-control-solid @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}"
                                   placeholder="support@example.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-10">
                            <label class="form-label">Phone</label>
                            <input type="text" 
                                   name="phone" 
                                   class="form-control form-control-solid @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone') }}"
                                   placeholder="123456789">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-10">
                            <label class="form-label">Location</label>
                            <input type="text" 
                                   name="location" 
                                   class="form-control form-control-solid @error('location') is-invalid @enderror" 
                                   value="{{ old('location') }}"
                                   placeholder="City, Country">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{ route('admin.site-content.index') }}" class="btn btn-light btn-active-light-primary me-2">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    {!! getIcon('check', 'fs-3 me-2') !!}
                    Create Content
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sectionSelect = document.getElementById('sectionSelect');
            const fieldGroups = document.querySelectorAll('.field-group');

            sectionSelect.addEventListener('change', function() {
                const selectedSection = this.value;
                
                // Hide all field groups
                fieldGroups.forEach(group => {
                    group.style.display = 'none';
                });

                if (selectedSection) {
                    // Show relevant field groups
                    fieldGroups.forEach(group => {
                        const sections = group.getAttribute('data-sections').split(',');
                        if (sections.includes(selectedSection)) {
                            group.style.display = 'block';
                        }
                    });
                }
            });

            // Trigger change event if section is pre-selected (for old input)
            if (sectionSelect.value) {
                sectionSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
    @endpush

</x-default-layout>
