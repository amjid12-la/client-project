@extends('frontend.layouts.frontend')



@push('frontend-styles')
@endpush


@section('frontend-content')
    <!-- HERO -->
    <section class="hero">
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
            <form class="report-form" id="reportForm" enctype="multipart/form-data">
                @csrf
                <h2>Submit A Report</h2>
                <p class="text-muted small mb-4">Fields marked with <span class="text-danger">*</span> are required</p>

                <div class="form-group">
                    <label>Name <span class="text-danger">*</span></label>
                    <input type="text" name="individual_name" id="individual_name" value="{{ old('individual_name') }}"
                        placeholder="Enter individual name" required>
                    <div class="invalid-feedback" id="nameError" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label>Location <span class="text-danger">*</span></label>
                    <input type="text" name="location" id="location" value="{{ old('location') }}"
                        placeholder="Enter location" required>
                    <div class="invalid-feedback" id="locationError" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label>Photo <span class="text-danger">*</span></label>
                    <input type="file" name="photo" id="photoInput" accept="image/*" required>
                    <small class="text-muted d-block mt-1">Max 5MB, JPG/PNG/GIF</small>
                    <small class="text-success d-block mt-1" id="selectedFileName" style="display: none;">
                        <i class="fas fa-check-circle"></i> <span id="fileName"></span>
                    </small>
                    <div class="invalid-feedback" id="photoError" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label>Description <span class="text-danger">*</span></label>
                    <textarea name="narrative" id="narrative" rows="4" placeholder="Describe the issue..." required>{{ old('narrative') }}</textarea>
                    <div class="invalid-feedback" id="narrativeError" style="display: none;"></div>
                </div>

                <button type="submit" class="submit-btn" id="submitBtn">
                    <i class="fas fa-paper-plane me-2"></i> Submit Report
                </button>
            </form>
        </div>

    </section>

    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    {{-- {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!} --}}
                    <input type="text" data-kt-user-table-filter="search"
                        class="form-control form-control-solid w-250px ps-13" placeholder="{{ __('Search') }}"
                        id="reportSearchInput" />
                </div>
            </div>

            <div class="card-toolbar">

            </div>
        </div>

        <div class="card-body py-4">
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>






    <!-- SEARCHABLE LIBRARY -->
    <section class="table-section" id="report-table">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="table-heading mb-0">Approved Reports</h2>
            <div class="table-search-box">
                <input type="text" id="table-search-input" placeholder="Search reports..." />
                <button type="button" id="table-search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="report-table table-striped" id="frontend-reports-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($reports as $index => $report)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if ($report->photo_path)
                                    <img src="{{ $report->photo_url }}" class="table-img" alt="Report Photo">
                                @else
                                    <span class="text-muted">No photo</span>
                                @endif
                            </td>
                            <td>{{ $report->individual_name ?? 'N/A' }}</td>
                            <td>{{ $report->location ?? 'N/A' }}</td>
                            <td>{{ $report->incident_date ? $report->incident_date->format('M d, Y') : 'N/A' }}</td>
                            <td class="desc">
                                {{ Str::limit($report->narrative ?? 'No description', 100) }}
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary view-report-btn" data-bs-toggle="modal"
                                    data-bs-target="#reportModal" data-report-id="{{ $report->id }}"
                                    data-report-photo="{{ $report->photo_url }}"
                                    data-report-name="{{ $report->individual_name }}"
                                    data-report-location="{{ $report->location }}"
                                    data-report-date="{{ $report->incident_date ? $report->incident_date->format('M d, Y') : 'N/A' }}"
                                    data-report-narrative="{{ $report->narrative }}"
                                    data-report-submitted="{{ $report->created_at->format('M d, Y h:i A') }}">
                                    <i class="fas fa-eye me-1"></i> View
                                </button>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Report Details Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Report Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Left Side - Photo -->
                        <div class="col-md-4" id="modal-photo-container">
                            <div class="mb-3">
                                <label class="fw-bold fs-6 mb-2">Photo</label>
                                <div class="text-center">
                                    <img id="modal-photo" src="" alt="Report Photo"
                                        class="img-fluid rounded shadow-sm"
                                        style="max-height: 350px; width: 100%; object-fit: cover;">
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

                            <!-- Incident Date -->
                            <div class="mb-3">
                                <label class="fw-bold fs-6 mb-2 text-primary">
                                    <i class="fas fa-calendar me-2"></i>Incident Date
                                </label>
                                <p id="modal-date" class="text-gray-800 fs-5"></p>
                            </div>

                            <!-- Narrative -->
                            <div class="mb-3">
                                <label class="fw-bold fs-6 mb-2 text-primary">
                                    <i class="fas fa-file-alt me-2"></i>Description
                                </label>
                                <p id="modal-narrative" class="text-gray-800"
                                    style="white-space: pre-wrap; line-height: 1.6;"></p>
                            </div>

                            <!-- Submitted At -->
                            <div class="mb-3">
                                <label class="fw-bold fs-6 mb-2 text-primary">
                                    <i class="fas fa-clock me-2"></i>Submitted At
                                </label>
                                <p id="modal-submitted" class="text-gray-800"></p>
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
@endsection

@push('frontend-scripts')
    {{ $dataTable->scripts() }}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search Filter
            document.getElementById('reportSearchInput').addEventListener('keyup', function() {
                window.LaravelDataTables['reports-table'].search(this.value).draw();
            });
        });
    </script>
    <script>
        // Store selected photo file
        let selectedPhotoFile = null;

        // Initialize DataTables
        // $(document).ready(function() {
        //     var table = $('#frontend-reports-table').DataTable({
        //         pageLength: 10,
        //         lengthMenu: [
        //             [10, 25, 50, 100],
        //             [10, 25, 50, 100]
        //         ],
        //         dom: '<"top"l>rt<"bottom"ip><"clear">', // Length at top, info and pagination at bottom
        //         language: {
        //             lengthMenu: "Show<br>_MENU_<br>Entries per page",
        //             info: "Showing _START_ to _END_ of _TOTAL_ entries",
        //             infoEmpty: "Showing 0 to 0 of 0 entries",
        //             emptyTable: "No approved reports yet. Be the first to submit!",
        //             paginate: {
        //                 first: "First",
        //                 last: "Last",
        //                 next: "Next",
        //                 previous: "Previous"
        //             }
        //         },
        //         order: [
        //             [0, 'asc']
        //         ],
        //         columnDefs: [{
        //                 orderable: false,
        //                 targets: [1, 6]
        //             } // Disable sorting for Photo and Actions columns
        //         ],
        //         drawCallback: function(settings) {
        //             // Re-attach event listeners for view buttons after table redraw
        //             attachViewButtonListeners();
        //             // Scroll to top of table when pagination is clicked
        //             $('html, body').animate({
        //                 scrollTop: $('#frontend-reports-table').offset().top - 100
        //             }, 300);
        //         }
        //     });

        //     // Custom search functionality
        //     $('#table-search-input').on('keyup', function() {
        //         table.search(this.value).draw();
        //     });

        //     $('#table-search-btn').on('click', function() {
        //         var searchValue = $('#table-search-input').val();
        //         table.search(searchValue).draw();
        //     });

        //     // Search on Enter key
        //     $('#table-search-input').on('keypress', function(e) {
        //         if (e.which === 13) {
        //             table.search(this.value).draw();
        //         }
        //     });

        //     // Function to attach view button listeners
        //     function attachViewButtonListeners() {
        //         document.querySelectorAll('.view-report-btn').forEach(button => {
        //             button.removeEventListener('click', handleViewClick); // Remove old listeners
        //             button.addEventListener('click', handleViewClick);
        //         });
        //     }

        //     // View button click handler
        //     function handleViewClick() {
        //         const photo = this.getAttribute('data-report-photo');
        //         const name = this.getAttribute('data-report-name');
        //         const location = this.getAttribute('data-report-location');
        //         const date = this.getAttribute('data-report-date');
        //         const narrative = this.getAttribute('data-report-narrative');
        //         const submitted = this.getAttribute('data-report-submitted');

        //         // Update modal content
        //         if (photo && photo !== 'null') {
        //             document.getElementById('modal-photo').src = photo;
        //             document.getElementById('modal-photo-container').style.display = 'block';
        //         } else {
        //             document.getElementById('modal-photo-container').style.display = 'none';
        //         }

        //         document.getElementById('modal-name').textContent = name || 'N/A';
        //         document.getElementById('modal-location').textContent = location || 'N/A';
        //         document.getElementById('modal-date').textContent = date || 'N/A';
        //         document.getElementById('modal-narrative').textContent = narrative || 'No description';
        //         document.getElementById('modal-submitted').textContent = submitted || 'N/A';
        //     }

        //     // Initial attachment
        //     attachViewButtonListeners();
        // });

        // Smooth scroll to form when clicking Report button
        document.addEventListener('DOMContentLoaded', function() {
            // Always start from top on page load/reload
            window.scrollTo(0, 0);

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
                        // Clear photo error if exists
                        clearFieldError('photo');
                    } else {
                        selectedPhotoFile = null;
                        selectedFileName.style.display = 'none';
                    }
                });
            }

            // AJAX Form Submission
            const reportForm = document.getElementById('reportForm');
            if (reportForm) {
                reportForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Clear all previous errors
                    clearAllErrors();

                    // Disable submit button
                    const submitBtn = document.getElementById('submitBtn');
                    const originalBtnText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Submitting...';

                    // Create FormData
                    const formData = new FormData();
                    formData.append('_token', document.querySelector('input[name="_token"]').value);
                    formData.append('individual_name', document.getElementById('individual_name').value);
                    formData.append('location', document.getElementById('location').value);
                    formData.append('narrative', document.getElementById('narrative').value);

                    // Add photo file if selected
                    if (selectedPhotoFile) {
                        formData.append('photo', selectedPhotoFile);
                    }

                    // Send AJAX request
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
                                // Show success message
                                showSuccessMessage(data.message);

                                // Reset form
                                reportForm.reset();
                                selectedPhotoFile = null;
                                selectedFileName.style.display = 'none';

                                // Scroll to top to show success message
                                document.getElementById('report-form').scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'start'
                                });

                                // Reload page after 2 seconds to show new report
                                setTimeout(function() {
                                    window.location.reload();
                                }, 2000);
                            } else if (data.errors) {
                                // Show validation errors
                                displayErrors(data.errors);

                                // Re-enable submit button
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalBtnText;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showErrorMessage('An error occurred. Please try again.');

                            // Re-enable submit button
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnText;
                        });
                });
            }

            // Function to display validation errors
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

            // Function to clear all errors
            function clearAllErrors() {
                const errorDivs = document.querySelectorAll('.invalid-feedback');
                errorDivs.forEach(div => {
                    div.style.display = 'none';
                    div.textContent = '';
                });

                const inputFields = document.querySelectorAll('.form-group input, .form-group textarea');
                inputFields.forEach(field => {
                    field.classList.remove('is-invalid');
                });
            }

            // Function to clear specific field error
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

            // Clear error on input
            document.querySelectorAll('.form-group input, .form-group textarea').forEach(field => {
                field.addEventListener('input', function() {
                    const fieldName = this.name;
                    clearFieldError(fieldName);
                });
            });

            // Function to show success message
            function showSuccessMessage(message) {
                const formContainer = document.querySelector('.info-right');
                const existingAlert = formContainer.querySelector('.alert-success');

                if (existingAlert) {
                    existingAlert.remove();
                }

                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                alertDiv.setAttribute('role', 'alert');
                alertDiv.innerHTML = `
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Success!</strong> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;

                formContainer.insertBefore(alertDiv, reportForm);
            }

            // Function to show error message
            function showErrorMessage(message) {
                const formContainer = document.querySelector('.info-right');
                const existingAlert = formContainer.querySelector('.alert-danger');

                if (existingAlert) {
                    existingAlert.remove();
                }

                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                alertDiv.setAttribute('role', 'alert');
                alertDiv.innerHTML = `
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Error!</strong> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;

                formContainer.insertBefore(alertDiv, reportForm);
            }

            // Check if there's a hash in URL after initial scroll
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

            // Handle smooth scroll for all anchor links
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
                            // Update URL hash without jumping
                            history.pushState(null, null, href);
                        }
                    }
                });
            });

            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Hero Search button - scroll to table
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
    </script>
@endpush
