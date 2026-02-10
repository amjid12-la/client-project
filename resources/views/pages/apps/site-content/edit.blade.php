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

                <!-- Title Field -->
                @if(in_array($content->section, ['hero', 'info', 'footer_about', 'footer_contact']))
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
                        <div class="form-text">
                            @if($content->section === 'hero')
                                Main heading displayed in the hero section
                            @elseif($content->section === 'info')
                                Heading for the information section
                            @elseif($content->section === 'footer_about')
                                Footer about section heading
                            @else
                                Footer contact section heading
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Hero Background Image (Hero only) -->
                @if($content->section === 'hero')
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
                            <div class="mt-3 p-3 bg-light rounded border">
                                <label class="form-label fw-bold text-primary mb-2 small">
                                    <i class="fas fa-image me-1"></i>Current Image:
                                </label>
                                <div>
                                    <img src="{{ asset('frontend/images/' . $content->image) }}" 
                                         alt="Hero Background" 
                                         class="img-thumbnail" 
                                         style="max-width: 150px; height: auto;">
                                </div>
                            </div>
                        @else
                            <div class="mt-2 text-muted small">
                                <i class="fas fa-info-circle me-1"></i>
                                No image uploaded yet
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Subtitle Field (Hero only) -->
                @if($content->section === 'hero')
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
                @endif

                <!-- Content Field -->
                @if(in_array($content->section, ['info', 'footer_about']))
                    <div class="mb-10">
                        <label class="form-label">Content</label>
                        <textarea name="content" 
                                  class="form-control form-control-solid @error('content') is-invalid @enderror" 
                                  rows="5"
                                  placeholder="Enter content">{{ old('content', $content->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            @if($content->section === 'info')
                                Description text for the information section
                            @else
                                About text displayed in the footer
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Contact Fields (Footer Contact only) -->
                @if($content->section === 'footer_contact')
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

</x-default-layout>
