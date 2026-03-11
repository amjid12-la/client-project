<x-default-layout>

    @section('breadcrumbs')
        <ul class="breadcrumb fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-dark">
                <a href="{{ route('admin.reports.index') }}" class="text-dark text-hover-primary">Report Management</a>
            </li>
            <li class="breadcrumb-item text-muted">Approved Reports</li>
        </ul>
    @endsection

    <style>
        .dt-toolbar{
            display:none !important;
        }
        
        /* Custom Close Button for Report Details Modal */
        .btn-close-custom-report-admin {
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
        
        .btn-close-custom-report-admin i {
            color: #fff;
            font-size: 20px;
            transition: transform 0.3s ease;
        }
        
        .btn-close-custom-report-admin:hover {
            background: linear-gradient(135deg, #ff6348 0%, #ff4757 100%);
            transform: scale(1.1) rotate(90deg);
            box-shadow: 0 6px 20px rgba(255, 71, 87, 0.6);
        }
        
        .btn-close-custom-report-admin:hover i {
            transform: rotate(90deg);
        }
        
        /* Ensure modal content has proper positioning */
        #reportModal .modal-content {
            position: relative;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .btn-close-custom-report-admin {
                top: -15px;
                right: -15px;
                width: 40px;
                height: 40px;
            }
            
            .btn-close-custom-report-admin i {
                font-size: 18px;
            }
        }
    </style>

    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <a href="javascript:history.back()" class="btn btn-sm btn-light-primary me-3">
                    {!! getIcon('arrow-left', 'fs-3') !!}
                    Back
                </a>
                <h3 class="fw-bold m-0">Approved Reports ({{ $approvedReports->count() }})</h3>
            </div>
            <div class="card-toolbar">
                <!-- Search Bar -->
                <div class="d-flex align-items-center me-3">
                    <div class="position-relative">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-3 mt-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" id="approved-search" class="form-control form-control-sm ps-10 pe-10" placeholder="Search reports..." style="min-width: 250px;">
                        <button type="button" id="approved-search-clear" class="btn btn-sm btn-icon position-absolute end-0 top-50 translate-middle-y me-1" style="display: none;">
                            <i class="ki-duotone ki-cross fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </button>
                    </div>
                </div>
                <div class="card-toolbar">
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-light-warning me-2">
                        {!! getIcon('time', 'fs-2') !!}
                        Pending Reports
                    </a>
                    <a href="{{ route('admin.reports.archive') }}" class="btn btn-sm btn-light-danger">
                        {!! getIcon('archive', 'fs-2') !!}
                        Archive
                    </a>
                </div>
            </div>

            <div class="card-body py-4">
                @if ($approvedReports->isEmpty())
                    <div class="text-center py-10">
                        <div class="mb-5">
                            {!! getIcon('information-5', 'fs-5x text-muted') !!}
                        </div>
                        <h3 class="text-muted">No Approved Reports</h3>
                        <p class="text-muted">No reports have been approved yet.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="approved-reports-table">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-50px">#</th>
                                    <th class="min-w-125px">Individual Name</th>
                                    <th class="min-w-125px">Location</th>
                                    <th class="min-w-150px">Description</th>
                                    <th class="min-w-100px">Approved</th>
                                    <th class="min-w-100px">Reviewed By</th>
                                    <th class="text-end min-w-150px">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                @foreach ($approvedReports as $report)
                                    <tr>
                                        <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($report->photo_path)
                                                    <div class="symbol symbol-50px me-3">
                                                        <img src="{{ $report->photo_url }}" alt="">
                                                    </div>
                                                @endif
                                                <div>
                                                    <span
                                                        class="text-gray-800 fw-bold">{{ $report->individual_name ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ Str::words($report->location ?? 'N/A', 4, '...') }}</td>
                                        <td>
                                            <span class="text-gray-600">{{ Str::words($report->narrative ?? 'No description', 4, '...') }}</span>
                                        </td>
                                        <td data-order="{{ $report->reviewed_at ? $report->reviewed_at->timestamp : 0 }}">
                                            @if ($report->reviewed_at)
                                                <span class="badge badge-light-success">
                                                    {{ $report->reviewed_at->diffForHumans() }}
                                                </span>
                                            @else
                                                <span class="badge badge-light-secondary">
                                                    Not reviewed
                                                </span>
                                            @endif
                                        </td>

                                        <td>{{ Str::words($report->reviewer->name ?? 'N/A', 4, '...') }}</td>
                                        <td class="text-end">
                                            <button type="button"
                                                class="btn btn-sm btn-light btn-active-light-primary me-2 view-report-btn"
                                                data-bs-toggle="modal" data-bs-target="#reportModal"
                                                data-report-id="{{ $report->id }}"
                                                data-report-photo="{{ $report->photo_url }}"
                                                data-report-name="{{ $report->individual_name }}"
                                                data-report-location="{{ $report->location }}"
                                                data-report-narrative="{{ $report->narrative }}"
                                                data-report-submitted="{{ $report->created_at->format('M d, Y h:i A') }}"
                                                data-report-reviewed="{{ $report->reviewed_at?->format('M d, Y h:i A') ?? 'Not reviewed yet' }}"
                                                data-report-reviewer="{{ $report->reviewer->name ?? 'N/A' }}">
                                                {!! getIcon('eye', 'fs-3') !!}
                                                View
                                            </button>
                                            <form action="{{ route('admin.reports.destroy', $report->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm btn-light-danger btn-active-danger"
                                                    onclick="return confirm('Are you sure you want to delete this report?')">
                                                    {!! getIcon('trash', 'fs-3') !!}
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @push('styles')
                        <style>
                            /* Add spacing between header and data */
                            #approved-reports-table thead th {
                                padding-bottom: 16px !important;
                            }
                            
                            #approved-reports-table tbody td {
                                padding-top: 16px !important;
                            }
                            
                            /* Force left alignment for all cells except Actions */
                            #approved-reports-table td,
                            #approved-reports-table th {
                                text-align: left !important;
                            }
                            
                            #approved-reports-table td:last-child,
                            #approved-reports-table th:last-child {
                                text-align: right !important;
                            }
                            
                            /* DataTables Layout for Dashboard */
                            .dataTables_wrapper .dataTables_length {
                                float: left;
                                margin-bottom: 15px;
                            }

                            .dataTables_wrapper .dataTables_length select {
                                border: 1px solid #e4e6ef;
                                border-radius: 6px;
                                padding: 6px 30px 6px 10px;
                                margin: 0 8px;
                                outline: none;
                                font-size: 14px;
                            }

                            .dataTables_wrapper .dataTables_filter {
                                float: right;
                                margin-bottom: 15px;
                            }

                            .dataTables_wrapper .dataTables_filter label {
                                display: flex;
                                align-items: center;
                                gap: 10px;
                            }

                            .dataTables_wrapper .dataTables_filter input {
                                border: 1px solid #e4e6ef;
                                border-radius: 6px;
                                padding: 8px 15px;
                                outline: none;
                                font-size: 14px;
                                width: 250px;
                            }

                            .dataTables_wrapper .dataTables_filter input:focus {
                                border-color: #50cd89;
                                box-shadow: 0 0 0 0.2rem rgba(80, 205, 137, 0.1);
                            }

                            /* Bottom Row - Info and Pagination on same line */
                            .dataTables_wrapper .dataTables_info {
                                float: left;
                                padding-top: 10px;
                                font-size: 14px;
                                color: #7e8299;
                            }

                            .dataTables_wrapper .dataTables_paginate {
                                float: right;
                                padding-top: 10px;
                            }

                            .dataTables_wrapper::after {
                                content: "";
                                display: table;
                                clear: both;
                            }

                            .dataTables_wrapper .dataTables_paginate .paginate_button {
                                padding: 8px 14px;
                                margin: 0 3px;
                                border: 1px solid #e4e6ef;
                                border-radius: 6px;
                                background: #fff;
                                color: #7e8299 !important;
                                cursor: pointer;
                                transition: all 0.3s ease;
                                font-size: 14px;
                                text-decoration: none;
                                display: inline-block;
                            }

                            .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
                                background: #e8fff3;
                                border-color: #50cd89;
                                color: #50cd89 !important;
                            }

                            .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                                background: #50cd89 !important;
                                color: #fff !important;
                                border-color: #50cd89;
                                font-weight: 600;
                            }

                            .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
                                background: #47be7d !important;
                                color: #fff !important;
                            }

                            .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
                                opacity: 0.5;
                                cursor: not-allowed;
                                pointer-events: none;
                            }

                            .dataTables_wrapper .dataTables_paginate .ellipsis {
                                padding: 8px 10px;
                                color: #b5b5c3;
                            }
                        </style>
                    @endpush

                    @push('scripts')
                        <script>
                            $(document).ready(function() {
                                var table = $('#approved-reports-table').DataTable({
                                    order: [[4, 'desc']], // Sort by approved date descending - newest first (changed from 3 to 4)
                                    pageLength: 10,
                                    lengthMenu: [
                                        [10, 25, 50, 100],
                                        [10, 25, 50, 100]
                                    ],
                                    language: {
                                        lengthMenu: "Show _MENU_ Entries per page",
                                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                                        infoEmpty: "Showing 0 to 0 of 0 entries",
                                        infoFiltered: "(filtered from _MAX_ total entries)",
                                        search: "Search:",
                                        paginate: {
                                            first: "First",
                                            last: "Last",
                                            next: "Next",
                                            previous: "Previous"
                                        }
                                    },
                                    columnDefs: [{
                                            orderable: false,
                                            targets: [0, 6] // Disable sorting for # and Actions columns
                                        }
                                    ],
                                    dom: 'rtip', // Remove default search box
                                    drawCallback: function() {
                                        // Scroll to top of table when pagination is clicked
                                        $('html, body').animate({
                                            scrollTop: $('#approved-reports-table').offset().top - 100
                                        }, 300);
                                        
                                        // Force left alignment after each draw for both headers and data
                                        $('#approved-reports-table th, #approved-reports-table td').css('text-align', 'left');
                                        $('#approved-reports-table th:first-child, #approved-reports-table td:first-child').css('text-align', 'center');
                                        $('#approved-reports-table th:last-child, #approved-reports-table td:last-child').css('text-align', 'right');
                                    }
                                });

                                // Custom search functionality
                                $('#approved-search').on('keyup', function() {
                                    var searchValue = this.value;
                                    table.search(searchValue).draw();
                                    
                                    // Show/hide clear button
                                    if (searchValue.length > 0) {
                                        $('#approved-search-clear').show();
                                    } else {
                                        $('#approved-search-clear').hide();
                                    }
                                });

                                // Clear search button
                                $('#approved-search-clear').on('click', function() {
                                    $('#approved-search').val('');
                                    table.search('').draw();
                                    $(this).hide();
                                });

                                // View button click handler
                                $(document).on('click', '.view-report-btn', function() {
                                    const photo = $(this).data('report-photo');
                                    const name = $(this).data('report-name');
                                    const location = $(this).data('report-location');
                                    const narrative = $(this).data('report-narrative');
                                    const submitted = $(this).data('report-submitted');
                                    const reviewed = $(this).data('report-reviewed');
                                    const reviewer = $(this).data('report-reviewer');

                                    // Update modal content
                                    if (photo && photo !== 'null') {
                                        $('#modal-photo').attr('src', photo);
                                        $('#modal-photo-container').show();
                                    } else {
                                        $('#modal-photo-container').hide();
                                    }

                                    $('#modal-name').text(name || 'N/A');
                                    $('#modal-location').text(location || 'N/A');
                                    $('#modal-narrative').text(narrative || 'No description');
                                    $('#modal-submitted').text(submitted || 'N/A');

                                    // Show/hide reviewed info
                                    if (reviewed) {
                                        $('#modal-reviewed-container').show();
                                        $('#modal-reviewed').text(reviewed);
                                        $('#modal-reviewer').text(reviewer || 'N/A');
                                    } else {
                                        $('#modal-reviewed-container').hide();
                                    }
                                });
                            });
                        </script>
                    @endpush
                @endif
            </div>
        </div>

        <!-- Report Details Modal -->
        <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <!-- Close Button - Positioned Outside Top Right -->
                    <button type="button" class="btn-close-custom-report-admin" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title" id="reportModalLabel">Report Details</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Left Side - Photo -->
                            <div class="col-md-5" id="modal-photo-container">
                                <div class="mb-3">
                                    <label class="fw-bold fs-6 mb-2">Photo</label>
                                    <div class="text-center">
                                        <img id="modal-photo" src="" alt="Report Photo"
                                            class="img-fluid rounded shadow-sm"
                                            style="max-height: 500px; width: 100%; object-fit: cover; border: 3px solid #e9ecef;">
                                    </div>
                                </div>
                            </div>

                            <!-- Right Side - Details -->
                            <div class="col-md-7">
                                <!-- Individual Name -->
                                <div class="mb-3">
                                    <label class="fw-bold fs-6 mb-2 text-primary">
                                        {!! getIcon('user', 'fs-3 me-2') !!}Individual Name
                                    </label>
                                    <p id="modal-name" class="text-gray-800 fs-5"></p>
                                </div>

                                <!-- Location -->
                                <div class="mb-3">
                                    <label class="fw-bold fs-6 mb-2 text-primary">
                                        {!! getIcon('geolocation', 'fs-3 me-2') !!}Location
                                    </label>
                                    <p id="modal-location" class="text-gray-800 fs-5"></p>
                                </div>

                                <!-- Narrative -->
                                <div class="mb-3">
                                    <label class="fw-bold fs-6 mb-2 text-primary">
                                        {!! getIcon('document', 'fs-3 me-2') !!}Description
                                    </label>
                                    <p id="modal-narrative" class="text-gray-800"
                                        style="white-space: pre-wrap; line-height: 1.6;"></p>
                                </div>

                                <!-- Submitted At -->
                                <div class="mb-3">
                                    <label class="fw-bold fs-6 mb-2 text-primary">
                                        {!! getIcon('time', 'fs-3 me-2') !!}Submitted At
                                    </label>
                                    <p id="modal-submitted" class="text-gray-800"></p>
                                </div>

                                <!-- Reviewed Info (for approved/rejected) -->
                                <div id="modal-reviewed-container" style="display: none;">
                                    <div class="mb-3">
                                        <label class="fw-bold fs-6 mb-2 text-success">
                                            {!! getIcon('check-circle', 'fs-3 me-2') !!}Reviewed At
                                        </label>
                                        <p id="modal-reviewed" class="text-gray-800"></p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="fw-bold fs-6 mb-2 text-success">
                                            {!! getIcon('user-tick', 'fs-3 me-2') !!}Reviewed By
                                        </label>
                                        <p id="modal-reviewer" class="text-gray-800"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {!! getIcon('cross', 'fs-3 me-2') !!}Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

</x-default-layout>
