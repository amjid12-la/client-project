<x-default-layout>
    
    @section('breadcrumbs')
        <ul class="breadcrumb fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-dark">Report Management</li>
            <li class="breadcrumb-item text-muted">Pending Reports</li>
        </ul>
    @endsection

    <style>
        .dt-toolbar{
            display:none !important;
        }
    </style>

    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <a href="javascript:history.back()" class="btn btn-sm btn-light-primary me-3">
                    {!! getIcon('arrow-left', 'fs-3') !!}
                    Back
                </a>
                <h3 class="fw-bold m-0">Pending Reports ({{ $pendingReports->count() }})</h3>
            </div>
            <div class="card-toolbar">
                <!-- Search Bar -->
                <div class="d-flex align-items-center me-3">
                    <div class="position-relative">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-3 mt-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" id="pending-search" class="form-control form-control-sm ps-10 pe-10" placeholder="Search reports..." style="min-width: 250px;">
                        <button type="button" id="pending-search-clear" class="btn btn-sm btn-icon position-absolute end-0 top-50 translate-middle-y me-1" style="display: none;">
                            <i class="ki-duotone ki-cross fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </button>
                    </div>
                </div>
                <a href="{{ route('admin.reports.approved') }}" class="btn btn-sm btn-light-success me-2">
                    {!! getIcon('check-circle', 'fs-2') !!}
                    Approved Reports
                </a>
                <a href="{{ route('admin.reports.archive') }}" class="btn btn-sm btn-light-danger">
                    {!! getIcon('archive', 'fs-2') !!}
                    Archive
                </a>
            </div>
        </div>

        <div class="card-body py-4">
            @if($pendingReports->isEmpty())
                <div class="text-center py-10">
                    <div class="mb-5">
                        {!! getIcon('information-5', 'fs-5x text-muted') !!}
                    </div>
                    <h3 class="text-muted">No Pending Reports</h3>
                    <p class="text-muted">All reports have been reviewed.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="pending-reports-table">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-50px">#</th>
                                <th class="min-w-125px">Individual Name</th>
                                <th class="min-w-125px">Location</th>
                                <th class="min-w-150px">Description</th>
                                <th class="min-w-100px">Submitted</th>
                                <th class="text-end min-w-100px">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @foreach($pendingReports as $report)
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($report->photo_path)
                                                <div class="symbol symbol-50px me-3">
                                                    <img src="{{ $report->photo_url }}" alt="">
                                                </div>
                                            @endif
                                            <div>
                                                <span class="text-gray-800 fw-bold">{{ $report->individual_name ?? 'N/A' }}</span>
                                                @php
                                                    $duplicateData = $report->duplicate_check ? json_decode($report->duplicate_check, true) : null;
                                                @endphp
                                                @if($duplicateData && isset($duplicateData['has_duplicates']) && $duplicateData['has_duplicates'])
                                                    <br>
                                                    <span class="badge badge-warning mt-1">
                                                        <i class="fas fa-exclamation-triangle"></i> 
                                                        {{ $duplicateData['duplicate_count'] }} Possible Duplicate(s)
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ Str::words($report->location ?? 'N/A', 4, '...') }}</td>
                                    <td>
                                        <span class="text-gray-600">{{ Str::words($report->narrative ?? 'No description', 4, '...') }}</span>
                                    </td>
                                    <td data-order="{{ $report->created_at->timestamp }}">
                                        <span class="badge badge-light-primary">{{ $report->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-sm btn-light btn-active-light-primary">
                                            Review
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @push('styles')
                <style>
                    /* Add spacing between header and data */
                    #pending-reports-table thead th {
                        padding-bottom: 16px !important;
                    }
                    
                    #pending-reports-table tbody td {
                        padding-top: 16px !important;
                    }
                    
                    /* Force left alignment for all cells except Actions */
                    #pending-reports-table td,
                    #pending-reports-table th {
                        text-align: left !important;
                    }
                    
                    #pending-reports-table td:last-child,
                    #pending-reports-table th:last-child {
                        text-align: right !important;
                    }
                    
                    /* DataTables Layout */
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
                        border-color: #ffc700;
                        box-shadow: 0 0 0 0.2rem rgba(255, 199, 0, 0.1);
                    }

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
                        background: #fff9e6;
                        border-color: #ffc700;
                        color: #ffc700 !important;
                    }

                    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                        background: #ffc700 !important;
                        color: #fff !important;
                        border-color: #ffc700;
                        font-weight: 600;
                    }

                    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
                        background: #e6b300 !important;
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
                        var table = $('#pending-reports-table').DataTable({
                            order: [[4, 'desc']], // Sort by submitted date descending - newest first (changed from 3 to 4)
                            pageLength: 10,
                            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
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
                            columnDefs: [
                                { orderable: false, targets: [0, 5] } // Disable sorting for # and Actions columns
                            ],
                            dom: 'rtip', // Remove default search box
                            drawCallback: function() {
                                // Scroll to top of table when pagination is clicked
                                $('html, body').animate({
                                    scrollTop: $('#pending-reports-table').offset().top - 100
                                }, 300);
                                
                                // Force left alignment after each draw for both headers and data
                                $('#pending-reports-table th, #pending-reports-table td').css('text-align', 'left');
                                $('#pending-reports-table th:first-child, #pending-reports-table td:first-child').css('text-align', 'center');
                                $('#pending-reports-table th:last-child, #pending-reports-table td:last-child').css('text-align', 'right');
                            }
                        });

                        // Custom search functionality
                        $('#pending-search').on('keyup', function() {
                            var searchValue = this.value;
                            table.search(searchValue).draw();
                            
                            // Show/hide clear button
                            if (searchValue.length > 0) {
                                $('#pending-search-clear').show();
                            } else {
                                $('#pending-search-clear').hide();
                            }
                        });

                        // Clear search button
                        $('#pending-search-clear').on('click', function() {
                            $('#pending-search').val('');
                            table.search('').draw();
                            $(this).hide();
                        });
                    });
                </script>
                @endpush
            @endif
        </div>
    </div>

</x-default-layout>
