@extends('frontend.layouts.frontend')



@push('frontend-styles')
    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    
    <style>
        /* Custom Close Button for Image Popup Modal */
        .btn-close-custom {
            position: absolute;
            top: -20px;
            right: -10px;
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #ff4757 0%, #ff6348 100%);
            border: 3px solid #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1060;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 71, 87, 0.4);
        }
        
        .btn-close-custom i {
            color: #fff;
            font-size: 20px;
            transition: transform 0.3s ease;
        }
        
        .btn-close-custom:hover {
            background: linear-gradient(135deg, #ff6348 0%, #ff4757 100%);
            transform: scale(1.1) rotate(90deg);
            box-shadow: 0 6px 20px rgba(255, 71, 87, 0.6);
        }
        
        .btn-close-custom:hover i {
            transform: rotate(90deg);
        }
        
        /* Custom Close Button for Report Details Modal */
        .btn-close-custom-report {
            position: absolute;
            top: -20px;
            right: -20px;
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #ff4757 0%, #ff6348 100%);
            border: 3px solid #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1060;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 71, 87, 0.4);
        }
        
        .btn-close-custom-report i {
            color: #fff;
            font-size: 20px;
            transition: transform 0.3s ease;
        }
        
        .btn-close-custom-report:hover {
            background: linear-gradient(135deg, #ff6348 0%, #ff4757 100%);
            transform: scale(1.1) rotate(90deg);
            box-shadow: 0 6px 20px rgba(255, 71, 87, 0.6);
        }
        
        .btn-close-custom-report:hover i {
            transform: rotate(90deg);
        }
        
        /* Ensure modal content has proper positioning */
        #imagePopupModal .modal-content {
            position: relative;
        }
        
        #reportModal .modal-content {
            position: relative;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .btn-close-custom {
                top: -40px;
                right: 5px;
                width: 40px;
                height: 40px;
            }
            
            .btn-close-custom i {
                font-size: 18px;
            }
            
            .btn-close-custom-report {
                top: -15px;
                right: -15px;
                width: 40px;
                height: 40px;
            }
            
            .btn-close-custom-report i {
                font-size: 18px;
            }
        }
    </style>
@endpush


@section('frontend-content')
    <!-- HERO -->
    <section class="hero" style="background-image: url('{{ $heroContent && $heroContent->image ? asset('frontend/images/' . $heroContent->image) : asset('frontend/images/hero-pic.jpg') }}');">
        <div class="hero-content">
            <h1 class="hero-title">{{ $heroContent->title ?? 'Report Management System' }}</h1>
            <p class="hero-subtitle">
                {{ $heroContent->subtitle ?? 'Submit, track, and search reports with ease. Your voice matters in building a transparent community.' }}
            </p>
        </div>
        <div class="hero-buttons">
            <a href="#report-form">Report</a>
            <a href="#report-table">Search</a>
        </div>
    </section>

    <!-- INFO + FORM -->

    <section class="info-section">

        <!-- LEFT INFO (col-6) -->
        <div class="info-left">
            <div class="info-content">
                <h2>{{ $infoContent->title ?? 'Report Information' }}</h2>
                <p>
                    {{ $infoContent->content ?? 'Please provide accurate and complete information while submitting your report. Your contribution helps us maintain a reliable and searchable record for future reference.' }}
                </p>
            </div>
        </div>

        <!-- RIGHT FORM (col-6) -->
        <div class="info-right" id="report-form">
            @auth
            <form class="report-form" id="reportForm" enctype="multipart/form-data">
                @csrf
                <h2>Submit A Report</h2>

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="individual_name" id="individual_name" value="{{ old('individual_name') }}"
                        placeholder="Enter individual name" pattern="[A-Za-z\s]+" title="Name must contain only letters and spaces">
                    <div class="invalid-feedback" id="nameError" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" id="location" value="{{ old('location') }}"
                        placeholder="Enter location">
                    <div class="invalid-feedback" id="locationError" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label>Photo</label>
                    <input type="file" name="photo" id="photoInput" accept="image/*">
                    <small class="text-muted d-block mt-1">Max 5MB, JPG/PNG/GIF</small>
                    <div class="invalid-feedback" id="photoError" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="narrative" id="narrative" rows="4" placeholder="Describe the issue...">{{ old('narrative') }}</textarea>
                    <div class="invalid-feedback" id="narrativeError" style="display: none;"></div>
                </div>

                <!-- reCAPTCHA -->
                <div class="form-group">
                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.sitekey') }}"></div>
                    <div class="invalid-feedback" id="recaptchaError" style="display: none;"></div>
                </div>

                <button type="submit" class="submit-btn" id="submitBtn">
                    <i class="fas fa-paper-plane me-2"></i> Submit Report
                </button>
            </form>
            @else
            <div class="report-form">
                <h2>Submit A Report</h2>
                <p class="text-muted mb-4">Please login or register to submit a report</p>
                
                <div class="text-center py-5">
                    <i class="fas fa-lock fa-4x text-muted mb-4"></i>
                    <h4 class="mb-4">Authentication Required</h4>
                    <p class="text-muted mb-4">You need to be logged in to submit a report. Please login or create an account to continue.</p>
                    
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="{{ route('login') }}" class="submit-btn" style="width: auto; padding: 12px 30px; text-decoration: none; display: inline-block;">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </a>
                        <a href="{{ route('register') }}" class="submit-btn" style="width: auto; padding: 12px 30px; text-decoration: none; display: inline-block; background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                            <i class="fas fa-user-plus me-2"></i> Register
                        </a>
                    </div>
                </div>
            </div>
            @endauth
        </div>

    </section>

    <!-- MODERN DATATABLE SECTION -->
    <section class="datatable-section" id="report-table">
        <div class="datatable-card">
            <!-- Card Header -->
            <div class="datatable-header">
                <div class="datatable-title">
                    <h2>Approved Reports</h2>
                    <p class="text-muted mb-0">Browse and search through all approved reports</p>
                </div>
                <div class="datatable-search">
                    <div class="search-wrapper" style="position: relative;">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" placeholder="Search reports..." id="reportSearchInput" style="padding-right: 40px;" />
                        <button type="button" id="report-search-clear" class="btn btn-sm btn-icon position-absolute end-0 top-50 translate-middle-y me-2" style="display: none; background: transparent; border: none; padding: 0; width: 30px; height: 30px;">
                            <i class="fas fa-times" style="color: #9ca3af; font-size: 16px;"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="datatable-body">
                <div class="table-responsive">
                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </section>

    <!-- Report Details Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <!-- Close Button - Positioned Outside Top Right -->
                <button type="button" class="btn-close-custom-report" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" id="reportModalLabel">Report Details</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Left Side - Photo -->
                        <div class="col-md-4" id="modal-photo-container">
                            <div class="mb-3">
                                <label class="fw-bold fs-6 mb-2">Photo</label>
                                <div class="text-center">
                                    <img id="modal-photo" src="" alt="Report Photo"
                                        class="img-fluid rounded shadow-sm clickable-image"
                                        style="max-height: 350px; width: 100%; object-fit: cover; cursor: pointer;"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#imagePopupModal">
                                </div>
                            </div>
                        </div>

                        <!-- Right Side - Details -->
                        <div class="col-md-8">
                            <!-- Individual Name -->
                            <div class="mb-3">
                                <label class="fw-bold fs-6 mb-2 text-primary">
                                    <i class="fas fa-user me-2"></i>Individual Name
                                </label>
                                <p id="modal-name" class="text-gray-800 fs-5"></p>
                            </div>

                            <!-- Location -->
                            <div class="mb-3">
                                <label class="fw-bold fs-6 mb-2 text-primary">
                                    <i class="fas fa-map-marker-alt me-2"></i>Location
                                </label>
                                <p id="modal-location" class="text-gray-800 fs-5"></p>
                            </div>

                            <!-- Narrative -->
                            <div class="mb-3">
                                <label class="fw-bold fs-6 mb-2 text-primary">
                                    <i class="fas fa-file-alt me-2"></i>Description
                                </label>
                                <p id="modal-narrative" class="text-gray-800"
                                    style="white-space: pre-wrap; line-height: 1.6;"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Popup Modal -->
    <div class="modal fade" id="imagePopupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0 shadow-none">
                <!-- Close Button - Positioned Outside Top Right -->
                <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
                <div class="modal-body p-0">
                    <img id="popupModalImage" src="" alt="Full Size Image" class="img-fluid rounded shadow-lg" style="width: 100%; max-height: 80vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('frontend-scripts')
    {{ $dataTable->scripts() }}

    <script>
        // Store selected photo file
        let selectedPhotoFile = null;

        document.addEventListener('DOMContentLoaded', function() {
            // Always start from top on page load/reload - scroll to hero section
            window.scrollTo({
                top: 0,
                behavior: 'instant'
            });
            
            // Prevent any hash-based scrolling on initial load
            if (window.location.hash) {
                // Remove hash without triggering scroll
                history.replaceState(null, null, ' ');
            }

            // Animate hero section on page load
            setTimeout(function() {
                document.querySelector('.hero-title').classList.add('animate');
                document.querySelector('.hero-subtitle').classList.add('animate');
                document.querySelector('.hero-buttons').classList.add('animate');
            }, 100);

            // Intersection Observer for scroll-based animations
            const observerOptions = {
                threshold: 0.15, // Trigger when 15% of element is visible
                rootMargin: '0px 0px -50px 0px' // Trigger slightly before element enters viewport
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate');
                        
                        // If it's info-section, animate children with delay
                        if (entry.target.classList.contains('info-section')) {
                            setTimeout(function() {
                                entry.target.querySelector('.info-left')?.classList.add('animate');
                            }, 200);
                            setTimeout(function() {
                                entry.target.querySelector('.info-right')?.classList.add('animate');
                            }, 400);
                        }
                        
                        // Once animated, stop observing
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Observe sections for scroll animations
            const animateOnScroll = document.querySelectorAll('.info-section, .datatable-section');
            animateOnScroll.forEach(element => {
                observer.observe(element);
            });


            // DataTable Search Filter
            const searchInput = document.getElementById('reportSearchInput');
            const searchClearBtn = document.getElementById('report-search-clear');
            
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const searchValue = this.value;
                    if (window.LaravelDataTables && window.LaravelDataTables['reports-table']) {
                        window.LaravelDataTables['reports-table'].search(searchValue).draw();
                    }
                    
                    // Show/hide clear button
                    if (searchValue.length > 0) {
                        searchClearBtn.style.display = 'block';
                    } else {
                        searchClearBtn.style.display = 'none';
                    }
                });
            }

            // Clear search button
            if (searchClearBtn) {
                searchClearBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    if (window.LaravelDataTables && window.LaravelDataTables['reports-table']) {
                        window.LaravelDataTables['reports-table'].search('').draw();
                    }
                    this.style.display = 'none';
                });
            }

            // Handle View Button Clicks for Modal
            document.addEventListener('click', function(e) {
                if (e.target.closest('.view-report-btn')) {
                    const button = e.target.closest('.view-report-btn');
                    const photo = button.getAttribute('data-report-photo');
                    const name = button.getAttribute('data-report-name');
                    const location = button.getAttribute('data-report-location');
                    const date = button.getAttribute('data-report-date');
                    const narrative = button.getAttribute('data-report-narrative');
                    const submitted = button.getAttribute('data-report-submitted');

                    // Update modal content
                    if (photo && photo !== 'null') {
                        document.getElementById('modal-photo').src = photo;
                        document.getElementById('modal-photo-container').style.display = 'block';
                    } else {
                        document.getElementById('modal-photo-container').style.display = 'none';
                    }

                    document.getElementById('modal-name').textContent = name || 'N/A';
                    document.getElementById('modal-location').textContent = location || 'N/A';
                    document.getElementById('modal-narrative').textContent = narrative || 'No description';
                }
            });

            // Photo input - show selected file name and store file
            const photoInput = document.getElementById('photoInput');
            const selectedFileName = document.getElementById('selectedFileName');
            const fileName = document.getElementById('fileName');

            if (photoInput) {
                photoInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        selectedPhotoFile = this.files[0];
                        fileName.textContent = this.files[0].name;
                        selectedFileName.style.display = 'block';
                        clearFieldError('photo');
                    } else {
                        selectedPhotoFile = null;
                        selectedFileName.style.display = 'none';
                    }
                });
            }

            // Name field validation - Accept only letters and spaces (max 10 spaces)
            const nameInput = document.getElementById('individual_name');
            if (nameInput) {
                nameInput.addEventListener('input', function(e) {
                    const value = e.target.value;
                    const regex = /^[A-Za-z\s]*$/;
                    
                    // Count spaces
                    const spaceCount = (value.match(/ /g) || []).length;
                    
                    if (!regex.test(value)) {
                        // Remove invalid characters
                        e.target.value = value.replace(/[^A-Za-z\s]/g, '');
                        nameInput.classList.add('is-invalid');
                        const nameError = document.getElementById('nameError');
                        if (nameError) {
                            nameError.textContent = 'Name must contain only letters and spaces';
                            nameError.style.display = 'block';
                        }
                    } else if (spaceCount > 10) {
                        // Remove extra spaces
                        let cleanValue = value;
                        let spaces = 0;
                        let result = '';
                        for (let i = 0; i < cleanValue.length; i++) {
                            if (cleanValue[i] === ' ') {
                                spaces++;
                                if (spaces <= 10) {
                                    result += cleanValue[i];
                                }
                            } else {
                                result += cleanValue[i];
                            }
                        }
                        e.target.value = result;
                        nameInput.classList.add('is-invalid');
                        const nameError = document.getElementById('nameError');
                        if (nameError) {
                            nameError.textContent = 'Name can contain maximum 10 spaces';
                            nameError.style.display = 'block';
                        }
                    } else {
                        nameInput.classList.remove('is-invalid');
                        clearFieldError('individual_name');
                    }
                });
                
                // Validate on blur
                nameInput.addEventListener('blur', function(e) {
                    const value = e.target.value.trim();
                    const regex = /^[A-Za-z\s]+$/;
                    const spaceCount = (value.match(/ /g) || []).length;
                    
                    if (value && !regex.test(value)) {
                        nameInput.classList.add('is-invalid');
                        const nameError = document.getElementById('nameError');
                        if (nameError) {
                            nameError.textContent = 'Name must contain only letters and spaces';
                            nameError.style.display = 'block';
                        }
                    } else if (spaceCount > 10) {
                        nameInput.classList.add('is-invalid');
                        const nameError = document.getElementById('nameError');
                        if (nameError) {
                            nameError.textContent = 'Name can contain maximum 10 spaces';
                            nameError.style.display = 'block';
                        }
                    }
                });
            }

            // AJAX Form Submission
            const reportForm = document.getElementById('reportForm');
            if (reportForm) {
                reportForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    clearAllErrors();

                    // Validate name field before submission
                    const nameValue = nameInput.value.trim();
                    const nameRegex = /^[A-Za-z\s]+$/;
                    const spaceCount = (nameValue.match(/ /g) || []).length;
                    
                    if (nameValue && (!nameRegex.test(nameValue) || spaceCount > 10)) {
                        const nameError = document.getElementById('nameError');
                        if (nameError) {
                            if (!nameRegex.test(nameValue)) {
                                nameError.textContent = 'Name must contain only letters and spaces';
                            } else {
                                nameError.textContent = 'Name can contain maximum 10 spaces';
                            }
                            nameError.style.display = 'block';
                        }
                        nameInput.classList.add('is-invalid');
                        nameInput.focus();
                        return false;
                    }

                    const submitBtn = document.getElementById('submitBtn');
                    const originalBtnText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Submitting...';

                    const formData = new FormData();
                    formData.append('_token', document.querySelector('input[name="_token"]').value);
                    formData.append('individual_name', document.getElementById('individual_name').value);
                    formData.append('location', document.getElementById('location').value);
                    formData.append('narrative', document.getElementById('narrative').value);

                    // Add reCAPTCHA response
                    const recaptchaResponse = grecaptcha.getResponse();
                    if (!recaptchaResponse) {
                        const recaptchaError = document.getElementById('recaptchaError');
                        recaptchaError.textContent = 'Please complete the reCAPTCHA verification';
                        recaptchaError.style.display = 'block';
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                        return;
                    }
                    formData.append('g-recaptcha-response', recaptchaResponse);

                    if (selectedPhotoFile) {
                        formData.append('photo', selectedPhotoFile);
                    }

                    fetch('{{ route('reports.store') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {

                                // Show success alert using toastr
                                toastr.success(data.message);

                                // Reset form
                                reportForm.reset();
                                selectedPhotoFile = null;
                                if (selectedFileName) {
                                    selectedFileName.style.display = 'none';
                                }

                                // Reset reCAPTCHA
                                grecaptcha.reset();

                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalBtnText;

                                // Refresh table without page reload
                                if (window.LaravelDataTables && window.LaravelDataTables['reports-table']) {
                                    window.LaravelDataTables['reports-table'].ajax.reload(null, false);
                                }

                            } else if (data.errors) {
                                displayErrors(data.errors);
                                
                                // Reset reCAPTCHA on error
                                grecaptcha.reset();
                                
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalBtnText;
                            }
                        })

                        .catch(error => {
                            console.error('Error:', error);
                            // Reset reCAPTCHA on error
                            grecaptcha.reset();
                            // Removed error alert - just log to console
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnText;
                        });
                });
            }

            function displayErrors(errors) {
                for (const [field, messages] of Object.entries(errors)) {
                    const errorDiv = document.getElementById(field + 'Error');
                    const inputField = document.getElementById(field === 'photo' ? 'photoInput' : field);
                    if (errorDiv && inputField) {
                        errorDiv.textContent = messages[0];
                        errorDiv.style.display = 'block';
                        inputField.classList.add('is-invalid');
                    }
                }
            }

            function clearAllErrors() {
                document.querySelectorAll('.invalid-feedback').forEach(div => {
                    div.style.display = 'none';
                    div.textContent = '';
                });
                document.querySelectorAll('.form-group input, .form-group textarea').forEach(field => {
                    field.classList.remove('is-invalid');
                });
            }

            function clearFieldError(fieldName) {
                const errorDiv = document.getElementById(fieldName + 'Error');
                const inputField = document.getElementById(fieldName === 'photo' ? 'photoInput' : fieldName);
                if (errorDiv) {
                    errorDiv.style.display = 'none';
                    errorDiv.textContent = '';
                }
                if (inputField) {
                    inputField.classList.remove('is-invalid');
                }
            }

            document.querySelectorAll('.form-group input, .form-group textarea').forEach(field => {
                field.addEventListener('input', function() {
                    clearFieldError(this.name);
                });
            });

            setTimeout(function() {
                if (window.location.hash) {
                    const target = document.querySelector(window.location.hash);
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            }, 100);

            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href !== '#') {
                        e.preventDefault();
                        const target = document.querySelector(href);
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                            history.pushState(null, null, href);
                        }
                    }
                });
            });

            setTimeout(function() {
                document.querySelectorAll('.alert').forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            const heroSearchBtn = document.querySelector('.hero-buttons a[href="#report-table"]');
            if (heroSearchBtn) {
                heroSearchBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tableSection = document.getElementById('report-table');
                    if (tableSection) {
                        tableSection.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            }
        });
        
        // Handle image popup - Fixed to get correct image source
        const imagePopupModal = document.getElementById('imagePopupModal');
        const modalPhoto = document.getElementById('modal-photo');
        
        if (imagePopupModal && modalPhoto) {
            // When modal photo is clicked, update popup image
            modalPhoto.addEventListener('click', function() {
                const popupImage = document.getElementById('popupModalImage');
                if (popupImage && this.src) {
                    popupImage.src = this.src;
                }
            });
            
            // When image popup is closed, also close the report modal
            imagePopupModal.addEventListener('hidden.bs.modal', function () {
                const popupImage = document.getElementById('popupModalImage');
                if (popupImage) {
                    popupImage.src = '';
                }
                
                // Close the report details modal as well
                const reportModal = document.getElementById('reportModal');
                if (reportModal) {
                    const bsReportModal = bootstrap.Modal.getInstance(reportModal);
                    if (bsReportModal) {
                        bsReportModal.hide();
                    }
                }
                
                // Remove any remaining backdrops
                setTimeout(function() {
                    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                        backdrop.remove();
                    });
                    // Remove modal-open class from body
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                }, 100);
            });
        }
    </script>
@endpush
