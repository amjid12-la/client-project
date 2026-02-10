<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportManagementController extends Controller
{
    public function index()
    {
        $pendingReports = Report::pending()
            ->latest()
            ->get();

        return view('pages.apps.report-management.index', compact('pendingReports'));
    }

    public function show(Report $report)
    {
        return view('pages.apps.report-management.show', compact('report'));
    }

    public function approve(Report $report)
    {
        $report->update([
            'status' => 'approved',
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        // Mark notification as read
        Auth::user()->unreadNotifications
            ->where('data->report_id', $report->id)
            ->markAsRead();

        return redirect()->route('admin.reports.index')
            ->with('success', 'Report approved successfully!');
    }

    public function reject(Request $request, Report $report)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string',
        ]);

        $report->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        // Mark notification as read
        Auth::user()->unreadNotifications
            ->where('data->report_id', $report->id)
            ->markAsRead();

        return redirect()->route('admin.reports.index')
            ->with('success', 'Report rejected and moved to archive.');
    }

    public function archive()
    {
        $rejectedReports = Report::rejected()
            ->latest('reviewed_at')
            ->get();

        return view('pages.apps.report-management.archive', compact('rejectedReports'));
    }

    public function approved()
    {
        $approvedReports = Report::approved()
            ->latest('reviewed_at')
            ->get();

        return view('pages.apps.report-management.approved', compact('approvedReports'));
    }

    public function destroy(Report $report)
    {
        // Delete the photo file if it exists
        if ($report->photo_path && file_exists(public_path($report->photo_path))) {
            unlink(public_path($report->photo_path));
        }

        // Delete the report
        $report->delete();

        return redirect()->back()
            ->with('success', 'Report deleted successfully!');
    }
}
