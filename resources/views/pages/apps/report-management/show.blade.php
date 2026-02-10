<x-default-layout>

    @section('title')
        Review Report
    @endsection

    @section('breadcrumbs')
        <ul class="breadcrumb fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">
                <a href="{{ route('admin.reports.index') }}" class="text-muted text-hover-primary">Report Management</a>
            </li>
            <li class="breadcrumb-item text-dark">Review Report</li>
        </ul>
    @endsection

    <div class="row g-5">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Report Details</h3>
                    <div class="card-toolbar">
                        <span class="badge badge-light-{{ $report->status === 'pending' ? 'warning' : ($report->status === 'approved' ? 'success' : 'danger') }}">
                            {{ ucfirst($report->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    @if($report->photo_path)
                        <div class="mb-7">
                            <label class="fw-bold fs-6 mb-2">Photo</label>
                            <div>
                                <img src="{{ $report->photo_url }}" alt="Report Photo" class="img-fluid rounded clickable-image" style="max-height: 400px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#imageModal" data-image-src="{{ $report->photo_url }}">
                            </div>
                        </div>
                    @endif

                    <div class="mb-7">
                        <label class="fw-bold fs-6 mb-2">Individual Name</label>
                        <div class="text-gray-800">{{ $report->individual_name ?? 'Not provided' }}</div>
                    </div>

                    <div class="mb-7">
                        <label class="fw-bold fs-6 mb-2">Location</label>
                        <div class="text-gray-800">{{ $report->location ?? 'Not provided' }}</div>
                    </div>

                    <div class="mb-7">
                        <label class="fw-bold fs-6 mb-2">Narrative</label>
                        <div class="text-gray-800">{{ $report->narrative ?? 'Not provided' }}</div>
                    </div>

                    <div class="mb-7">
                        <label class="fw-bold fs-6 mb-2">Submitted</label>
                        <div class="text-gray-800">{{ $report->created_at->format('F d, Y h:i A') }}</div>
                    </div>

                    @if($report->reviewed_at)
                        <div class="mb-7">
                            <label class="fw-bold fs-6 mb-2">Reviewed</label>
                            <div class="text-gray-800">
                                {{ $report->reviewed_at->format('F d, Y h:i A') }}
                                @if($report->reviewer)
                                    by {{ $report->reviewer->name }}
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($report->admin_notes)
                        <div class="mb-7">
                            <label class="fw-bold fs-6 mb-2">Admin Notes</label>
                            <div class="text-gray-800">{{ $report->admin_notes }}</div>
                        </div>
                    @endif

                    @php
                        $duplicateData = $report->duplicate_check ? json_decode($report->duplicate_check, true) : null;
                    @endphp
                    @if($duplicateData && isset($duplicateData['has_duplicates']) && $duplicateData['has_duplicates'])
                        <div class="mb-7">
                            <div class="alert alert-warning d-flex align-items-center p-5">
                                <i class="fas fa-exclamation-triangle fs-2x me-4"></i>
                                <div class="d-flex flex-column">
                                    <h4 class="mb-1 text-dark">Possible Duplicate Detected</h4>
                                    <span>This report may be similar to {{ $duplicateData['duplicate_count'] }} existing report(s)</span>
                                </div>
                            </div>

                            <div class="card bg-light">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Similar Reports</h5>
                                </div>
                                <div class="card-body">
                                    @foreach($duplicateData['duplicates'] as $duplicate)
                                        @php
                                            $similarReport = \App\Models\Report::find($duplicate['report_id']);
                                        @endphp
                                        @if($similarReport)
                                            <div class="border border-gray-300 rounded p-4 mb-3 bg-white">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div>
                                                        <span class="badge badge-{{ $similarReport->status === 'approved' ? 'success' : 'warning' }}">
                                                            {{ ucfirst($similarReport->status) }}
                                                        </span>
                                                        <span class="badge badge-info ms-2">
                                                            {{ $duplicate['similarity_score'] }}% Match
                                                        </span>
                                                    </div>
                                                    <a href="{{ route('admin.reports.show', $similarReport->id) }}" 
                                                       class="btn btn-sm btn-light-primary" 
                                                       target="_blank">
                                                        View Report
                                                    </a>
                                                </div>
                                                
                                                <div class="mb-2">
                                                    <strong>Name:</strong> {{ $similarReport->individual_name ?? 'N/A' }}
                                                </div>
                                                
                                                @if($similarReport->location)
                                                    <div class="mb-2">
                                                        <strong>Location:</strong> {{ $similarReport->location }}
                                                    </div>
                                                @endif
                                                
                                                @if($similarReport->narrative)
                                                    <div class="mb-2">
                                                        <strong>Description:</strong> {{ Str::limit($similarReport->narrative, 150) }}
                                                    </div>
                                                @endif
                                                
                                                <div class="text-muted small">
                                                    <strong>Matched Fields:</strong> {{ $duplicate['matched_fields'] }}
                                                </div>
                                                
                                                <div class="text-muted small">
                                                    <strong>Submitted:</strong> {{ $similarReport->created_at->format('M d, Y h:i A') }}
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            @if($report->status === 'pending')
                <div class="card mb-5">
                    <div class="card-header">
                        <h3 class="card-title">Actions</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.reports.approve', $report->id) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                {!! getIcon('check-circle', 'fs-2') !!}
                                Approve Report
                            </button>
                        </form>

                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            {!! getIcon('cross-circle', 'fs-2') !!}
                            Reject Report
                        </button>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quick Links</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-light w-100 mb-2">
                        Back to Pending Reports
                    </a>
                    <a href="{{ route('admin.reports.approved') }}" class="btn btn-light w-100 mb-2">
                        View Approved Reports
                    </a>
                    <a href="{{ route('admin.reports.archive') }}" class="btn btn-light w-100">
                        View Archive
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.reports.reject', $report->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Report</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Admin Notes (Optional)</label>
                            <textarea name="admin_notes" class="form-control" rows="4" placeholder="Add notes about why this report was rejected..."></textarea>
                        </div>
                        <div class="alert alert-warning">
                            <strong>Warning:</strong> This report will be moved to the archive.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Image Popup Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0 shadow-none">
                <!-- Close Button - Positioned Outside Top Right -->
                <button type="button" class="btn-close-custom-admin" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
                <div class="modal-body p-0">
                    <img id="modalImage" src="" alt="Full Size Image" class="img-fluid rounded shadow-lg" style="width: 100%; max-height: 80vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <style>
        /* Custom Close Button for Image Modal */
        .btn-close-custom-admin {
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
        
        .btn-close-custom-admin i {
            color: #fff;
            font-size: 20px;
            transition: transform 0.3s ease;
        }
        
        .btn-close-custom-admin:hover {
            background: linear-gradient(135deg, #ff6348 0%, #ff4757 100%);
            transform: scale(1.1) rotate(90deg);
            box-shadow: 0 6px 20px rgba(255, 71, 87, 0.6);
        }
        
        .btn-close-custom-admin:hover i {
            transform: rotate(90deg);
        }
        
        /* Ensure modal content has proper positioning */
        #imageModal .modal-content {
            position: relative;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .btn-close-custom-admin {
                top: -40px;
                right: 5px;
                width: 40px;
                height: 40px;
            }
            
            .btn-close-custom-admin i {
                font-size: 18px;
            }
        }
    </style>
    <script>
        // Handle image click to show in modal
        document.addEventListener('DOMContentLoaded', function() {
            const imageModal = document.getElementById('imageModal');
            if (imageModal) {
                imageModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const imageSrc = button.getAttribute('data-image-src');
                    const modalImage = document.getElementById('modalImage');
                    modalImage.src = imageSrc;
                });
            }
        });
    </script>
    @endpush

</x-default-layout>
