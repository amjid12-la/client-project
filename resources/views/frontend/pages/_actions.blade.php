<button type="button" class="btn btn-sm btn-primary view-report-btn" data-bs-toggle="modal" data-bs-target="#reportModal"
    data-report-id="{{ $report->id }}" data-report-photo="{{ $report->photo_url }}"
    data-report-name="{{ $report->individual_name }}" data-report-location="{{ $report->location }}"
    data-report-date="{{ $report->created_at->format('M d, Y') }}"
    data-report-narrative="{{ $report->narrative }}"
    data-report-submitted="{{ $report->created_at->format('M d, Y h:i A') }}">
    <i class="fas fa-eye me-1"></i> View
</button>
