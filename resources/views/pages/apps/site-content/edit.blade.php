<x-default-layout>

    @section('title')
        Edit {{ ucfirst(str_replace('_', ' ', $content->section)) }} Content
    @endsection

    @section('breadcrumbs')
        <ul class="breadcrumb fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">
                <a href="{{ route('admin.site-content.index') }}" class="text-muted text-hover-primary">Content Management</a>
            </li>
            <li class="breadcrumb-item text-dark">Edit {{ ucfirst(str_replace('_', ' ', $content->section)) }}</li>
        </ul>
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.site-content.index') }}" class="btn btn-sm btn-light-primary me-3">
                    {!! getIcon('arrow-left', 'fs-3') !!}
                    Back
                </a>
                <h3 class="card-title">Edit {{ ucfirst(str_replace('_', ' ', $content->section)) }} Section</h3>
            </div>
        </div>

        <form action="{{ route('admin.site-content.update', $content->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

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

                <!-- Hero Section -->
                @if($content->section === 'hero')
                    <div class="mb-10">
                        <label class="form-label required">Title</label>
                        <input type="text" 
                               name="title" 
                               class="form-control form-control-solid @error('title') is-invalid @enderror" 
                               value="{{ old('title', $content->title) }}"
                               placeholder="Enter title">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Main heading displayed in the hero section</div>
                    </div>

                    <div class="mb-10">
                        <label class="form-label">Subtitle</label>
                        <textarea name="subtitle" 
                                  class="form-control form-control-solid @error('subtitle') is-invalid @enderror" 
                                  rows="3"
                                  placeholder="Enter subtitle">{{ old('subtitle', $content->subtitle) }}</textarea>
                        @error('subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Subtitle text displayed below the hero title</div>
                    </div>

                    <div class="mb-10">
                        <label class="form-label">Background Image</label>
                        
                        <input type="file" 
                               name="image" 
                               class="form-control @error('image') is-invalid @enderror"
                               accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-upload me-1"></i>
                            Upload hero section background image (Max 5MB, JPG/PNG/GIF)
                        </div>
                        
                        @if($content->image)
                            <div class="mt-3 p-3 bg-light rounded border position-relative" id="hero-image-preview">
                                <label class="form-label fw-bold text-primary mb-2 small">
                                    <i class="fas fa-image me-1"></i>Current Image:
                                </label>
                                <div class="position-relative d-inline-block">
                                    <img src="{{ asset('frontend/images/' . $content->image) }}" 
                                         alt="Hero Background" 
                                         class="img-thumbnail" 
                                         style="max-width: 150px; height: auto;">
                                    <button type="button" 
                                            class="btn btn-sm btn-danger position-absolute" 
                                            onclick="removeImage('hero', {{ $content->id }})"
                                            style="top: 5px; right: 5px; padding: 2px 6px; font-size: 12px; border-radius: 3px;"
                                            title="Remove image">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="mt-2 text-muted small">
                                <i class="fas fa-info-circle me-1"></i>
                                No image uploaded yet
                            </div>
                        @endif
                    </div>

                    <div class="separator separator-dashed my-7"></div>
                    <h4 class="mb-5">Hero Styling</h4>

                    <div class="mb-10">
                        <label class="form-label">Text Color</label>
                        <div class="input-group">
                            <input type="color" 
                                   name="hero_text_color" 
                                   id="hero_text_color"
                                   class="form-control form-control-color @error('hero_text_color') is-invalid @enderror" 
                                   value="{{ old('hero_text_color', $content->hero_text_color ?? '#ffffff') }}"
                                   style="width: 80px; height: 45px;">
                            <input type="text" 
                                   id="hero_text_color_text"
                                   class="form-control form-control-solid" 
                                   value="{{ old('hero_text_color', $content->hero_text_color ?? '#ffffff') }}"
                                   readonly
                                   placeholder="#ffffff">
                        </div>
                        @error('hero_text_color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Choose hero text color (title & subtitle)</div>
                    </div>

                    <div class="mb-10">
                        <label class="form-label">Button Background Color</label>
                        <div class="input-group">
                            <input type="color" 
                                   name="button_bg_color" 
                                   id="button_bg_color"
                                   class="form-control form-control-color @error('button_bg_color') is-invalid @enderror" 
                                   value="{{ old('button_bg_color', $content->button_bg_color ?? '#007bff') }}"
                                   style="width: 80px; height: 45px;">
                            <input type="text" 
                                   id="button_bg_color_text"
                                   class="form-control form-control-solid" 
                                   value="{{ old('button_bg_color', $content->button_bg_color ?? '#007bff') }}"
                                   readonly
                                   placeholder="#007bff">
                        </div>
                        @error('button_bg_color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Choose button background color</div>
                    </div>

                    <div class="mb-10">
                        <label class="form-label">Button Text Color</label>
                        <div class="input-group">
                            <input type="color" 
                                   name="button_text_color" 
                                   id="button_text_color"
                                   class="form-control form-control-color @error('button_text_color') is-invalid @enderror" 
                                   value="{{ old('button_text_color', $content->button_text_color ?? '#ffffff') }}"
                                   style="width: 80px; height: 45px;">
                            <input type="text" 
                                   id="button_text_color_text"
                                   class="form-control form-control-solid" 
                                   value="{{ old('button_text_color', $content->button_text_color ?? '#ffffff') }}"
                                   readonly
                                   placeholder="#ffffff">
                        </div>
                        @error('button_text_color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Choose button text color</div>
                    </div>
                @endif

                <!-- Info Section -->
                @if($content->section === 'info')
                    <div class="mb-10">
                        <label class="form-label required">Title</label>
                        <input type="text" 
                               name="title" 
                               class="form-control form-control-solid @error('title') is-invalid @enderror" 
                               value="{{ old('title', $content->title) }}"
                               placeholder="Enter title">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Heading for the information section</div>
                    </div>

                    <div class="mb-10">
                        <label class="form-label">Content</label>
                        <textarea name="content" 
                                  class="form-control form-control-solid @error('content') is-invalid @enderror" 
                                  rows="5"
                                  placeholder="Enter content">{{ old('content', $content->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Description text for the information section</div>
                    </div>

                    <div class="separator separator-dashed my-7"></div>
                    <h4 class="mb-5">Info Section Styling</h4>

                    <div class="mb-10">
                        <label class="form-label">Background Image</label>
                        
                        <input type="file" 
                               name="image" 
                               class="form-control @error('image') is-invalid @enderror"
                               accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-upload me-1"></i>
                            Upload info section background image (Max 5MB, JPG/PNG/GIF)
                        </div>
                        
                        @if($content->image)
                            <div class="mt-3 p-3 bg-light rounded border position-relative" id="info-image-preview">
                                <label class="form-label fw-bold text-primary mb-2 small">
                                    <i class="fas fa-image me-1"></i>Current Image:
                                </label>
                                <div class="position-relative d-inline-block">
                                    <img src="{{ asset('frontend/images/' . $content->image) }}" 
                                         alt="Info Background" 
                                         class="img-thumbnail" 
                                         style="max-width: 150px; height: auto;">
                                    <button type="button" 
                                            class="btn btn-sm btn-danger position-absolute" 
                                            onclick="removeImage('info', {{ $content->id }})"
                                            style="top: 5px; right: 5px; padding: 2px 6px; font-size: 12px; border-radius: 3px;"
                                            title="Remove image">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="mt-2 text-muted small">
                                <i class="fas fa-info-circle me-1"></i>
                                No image uploaded yet
                            </div>
                        @endif
                    </div>

                    <div class="mb-10">
                        <label class="form-label">Background Color</label>
                        <div class="input-group">
                            <input type="color" 
                                   name="background_color" 
                                   id="info_background_color"
                                   class="form-control form-control-color @error('background_color') is-invalid @enderror" 
                                   value="{{ old('background_color', $content->background_color ?? '#f8f9fa') }}"
                                   style="width: 80px; height: 45px;">
                            <input type="text" 
                                   id="info_background_color_text"
                                   class="form-control form-control-solid" 
                                   value="{{ old('background_color', $content->background_color ?? '#f8f9fa') }}"
                                   readonly
                                   placeholder="#f8f9fa">
                        </div>
                        @error('background_color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Choose info section background color (used if no image is uploaded)</div>
                    </div>

                    <div class="mb-10">
                        <label class="form-label">Text Color</label>
                        <div class="input-group">
                            <input type="color" 
                                   name="info_text_color" 
                                   id="info_text_color"
                                   class="form-control form-control-color @error('info_text_color') is-invalid @enderror" 
                                   value="{{ old('info_text_color', $content->info_text_color ?? '#000000') }}"
                                   style="width: 80px; height: 45px;">
                            <input type="text" 
                                   id="info_text_color_text"
                                   class="form-control form-control-solid" 
                                   value="{{ old('info_text_color', $content->info_text_color ?? '#000000') }}"
                                   readonly
                                   placeholder="#000000">
                        </div>
                        @error('info_text_color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Choose info section text color (title & content)</div>
                    </div>
                @endif

                <!-- Navbar Section -->
                @if($content->section === 'navbar')
                    <h4 class="mb-5">Navbar Styling</h4>
                    
                    <div class="mb-10">
                        <label class="form-label">Navbar Background Color</label>
                        <div class="input-group">
                            <input type="color" 
                                   name="navbar_bg_color" 
                                   id="navbar_bg_color"
                                   class="form-control form-control-color @error('navbar_bg_color') is-invalid @enderror" 
                                   value="{{ old('navbar_bg_color', $content->navbar_bg_color ?? '#ffffff') }}"
                                   style="width: 80px; height: 45px;">
                            <input type="text" 
                                   id="navbar_bg_color_text"
                                   class="form-control form-control-solid" 
                                   value="{{ old('navbar_bg_color', $content->navbar_bg_color ?? '#ffffff') }}"
                                   readonly
                                   placeholder="#ffffff">
                        </div>
                        @error('navbar_bg_color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Choose navbar background color</div>
                    </div>

                    <div class="mb-10">
                        <label class="form-label">Navbar Text Color</label>
                        <div class="input-group">
                            <input type="color" 
                                   name="navbar_text_color" 
                                   id="navbar_text_color"
                                   class="form-control form-control-color @error('navbar_text_color') is-invalid @enderror" 
                                   value="{{ old('navbar_text_color', $content->navbar_text_color ?? '#000000') }}"
                                   style="width: 80px; height: 45px;">
                            <input type="text" 
                                   id="navbar_text_color_text"
                                   class="form-control form-control-solid" 
                                   value="{{ old('navbar_text_color', $content->navbar_text_color ?? '#000000') }}"
                                   readonly
                                   placeholder="#000000">
                        </div>
                        @error('navbar_text_color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Choose navbar text/links color</div>
                    </div>
                @endif

                <!-- Footer Section (Combined) -->
                @if($content->section === 'footer')
                    <h4 class="mb-5">About Section</h4>
                    
                    <div class="mb-10">
                        <label class="form-label">About Title</label>
                        <input type="text" 
                               name="title" 
                               class="form-control form-control-solid @error('title') is-invalid @enderror" 
                               value="{{ old('title', $content->title) }}"
                               placeholder="Enter about title">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-10">
                        <label class="form-label">About Content</label>
                        <textarea name="content" 
                                  class="form-control form-control-solid @error('content') is-invalid @enderror" 
                                  rows="4"
                                  placeholder="Enter about content">{{ old('content', $content->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="separator separator-dashed my-7"></div>
                    <h4 class="mb-5">Contact Section</h4>

                    <div class="mb-10">
                        <label class="form-label">Contact Title</label>
                        <input type="text" 
                               name="contact_title" 
                               class="form-control form-control-solid @error('contact_title') is-invalid @enderror" 
                               value="{{ old('contact_title', $content->contact_title) }}"
                               placeholder="Enter contact title (e.g., Contact)">
                        @error('contact_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-10">
                        <label class="form-label">Email</label>
                        <input type="email" 
                               name="email" 
                               class="form-control form-control-solid @error('email') is-invalid @enderror" 
                               value="{{ old('email', $content->email) }}"
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
                               value="{{ old('phone', $content->phone) }}"
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
                               value="{{ old('location', $content->location) }}"
                               placeholder="City, Country">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="separator separator-dashed my-7"></div>
                    <h4 class="mb-5">Footer Styling</h4>

                    <div class="mb-10">
                        <label class="form-label">Background Color</label>
                        <div class="input-group">
                            <input type="color" 
                                   name="background_color" 
                                   id="background_color"
                                   class="form-control form-control-color @error('background_color') is-invalid @enderror" 
                                   value="{{ old('background_color', $content->background_color ?? '#1a1a1a') }}"
                                   style="width: 80px; height: 45px;">
                            <input type="text" 
                                   id="background_color_text"
                                   class="form-control form-control-solid" 
                                   value="{{ old('background_color', $content->background_color ?? '#1a1a1a') }}"
                                   readonly
                                   placeholder="#1a1a1a">
                        </div>
                        @error('background_color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Choose footer background color</div>
                    </div>

                    <div class="mb-10">
                        <label class="form-label">Text Color</label>
                        <div class="input-group">
                            <input type="color" 
                                   name="text_color" 
                                   id="text_color"
                                   class="form-control form-control-color @error('text_color') is-invalid @enderror" 
                                   value="{{ old('text_color', $content->text_color ?? '#ffffff') }}"
                                   style="width: 80px; height: 45px;">
                            <input type="text" 
                                   id="text_color_text"
                                   class="form-control form-control-solid" 
                                   value="{{ old('text_color', $content->text_color ?? '#ffffff') }}"
                                   readonly
                                   placeholder="#ffffff">
                        </div>
                        @error('text_color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Choose footer text color</div>
                    </div>
                @endif

            </div>

            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{ route('admin.site-content.index') }}" class="btn btn-light btn-active-light-primary me-2">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    {!! getIcon('check', 'fs-3 me-2') !!}
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        // Remove image function
        function removeImage(section, contentId) {
            if (!confirm('Are you sure you want to remove this image?')) {
                return;
            }

            // Show loading state
            const previewId = section + '-image-preview';
            const previewDiv = document.getElementById(previewId);
            if (previewDiv) {
                previewDiv.innerHTML = '<div class="text-center py-3"><i class="fas fa-spinner fa-spin"></i> Removing...</div>';
            }

            // Send AJAX request to remove image
            fetch(`/admin/site-content/${contentId}/remove-image`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Remove preview div and show "no image" message
                    if (previewDiv) {
                        const parentDiv = previewDiv.parentElement;
                        previewDiv.remove();
                        const noImageDiv = document.createElement('div');
                        noImageDiv.className = 'mt-2 text-muted small';
                        noImageDiv.innerHTML = '<i class="fas fa-info-circle me-1"></i>No image uploaded yet';
                        parentDiv.appendChild(noImageDiv);
                    }
                    
                    // Show success message
                    toastr.success(data.message || 'Image removed successfully!');
                } else {
                    // Show error and reload
                    toastr.error(data.message || 'Failed to remove image');
                    setTimeout(() => location.reload(), 1500);
                }
            })
            .catch(error => {
                // Log error silently and reload page
                console.error('Error removing image:', error);
                setTimeout(() => location.reload(), 500);
            });
        }

        // Color picker sync
        document.addEventListener('DOMContentLoaded', function() {
            // Footer background color
            const colorPicker = document.getElementById('background_color');
            const colorText = document.getElementById('background_color_text');
            if (colorPicker && colorText) {
                colorPicker.addEventListener('input', function() {
                    colorText.value = this.value;
                });
            }

            // Footer text color
            const textColorPicker = document.getElementById('text_color');
            const textColorText = document.getElementById('text_color_text');
            if (textColorPicker && textColorText) {
                textColorPicker.addEventListener('input', function() {
                    textColorText.value = this.value;
                });
            }

            // Hero text color
            const heroTextColorPicker = document.getElementById('hero_text_color');
            const heroTextColorText = document.getElementById('hero_text_color_text');
            if (heroTextColorPicker && heroTextColorText) {
                heroTextColorPicker.addEventListener('input', function() {
                    heroTextColorText.value = this.value;
                });
            }

            // Button background color
            const buttonBgColorPicker = document.getElementById('button_bg_color');
            const buttonBgColorText = document.getElementById('button_bg_color_text');
            if (buttonBgColorPicker && buttonBgColorText) {
                buttonBgColorPicker.addEventListener('input', function() {
                    buttonBgColorText.value = this.value;
                });
            }

            // Button text color
            const buttonTextColorPicker = document.getElementById('button_text_color');
            const buttonTextColorText = document.getElementById('button_text_color_text');
            if (buttonTextColorPicker && buttonTextColorText) {
                buttonTextColorPicker.addEventListener('input', function() {
                    buttonTextColorText.value = this.value;
                });
            }

            // Info background color
            const infoBgColorPicker = document.getElementById('info_background_color');
            const infoBgColorText = document.getElementById('info_background_color_text');
            if (infoBgColorPicker && infoBgColorText) {
                infoBgColorPicker.addEventListener('input', function() {
                    infoBgColorText.value = this.value;
                });
            }

            // Info text color
            const infoTextColorPicker = document.getElementById('info_text_color');
            const infoTextColorText = document.getElementById('info_text_color_text');
            if (infoTextColorPicker && infoTextColorText) {
                infoTextColorPicker.addEventListener('input', function() {
                    infoTextColorText.value = this.value;
                });
            }

            // Navbar background color
            const navbarBgColorPicker = document.getElementById('navbar_bg_color');
            const navbarBgColorText = document.getElementById('navbar_bg_color_text');
            if (navbarBgColorPicker && navbarBgColorText) {
                navbarBgColorPicker.addEventListener('input', function() {
                    navbarBgColorText.value = this.value;
                });
            }

            // Navbar text color
            const navbarTextColorPicker = document.getElementById('navbar_text_color');
            const navbarTextColorText = document.getElementById('navbar_text_color_text');
            if (navbarTextColorPicker && navbarTextColorText) {
                navbarTextColorPicker.addEventListener('input', function() {
                    navbarTextColorText.value = this.value;
                });
            }
        });
    </script>
    @endpush

</x-default-layout>
