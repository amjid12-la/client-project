<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get counts
        $pendingCount = Report::pending()->count();
        $approvedCount = Report::approved()->count();
        $rejectedCount = Report::rejected()->count();
        $totalCount = $pendingCount + $approvedCount + $rejectedCount;

        // Calculate percentages
        $pendingPercentage = $totalCount > 0 ? round(($pendingCount / $totalCount) * 100) : 0;
        $approvedPercentage = $totalCount > 0 ? round(($approvedCount / $totalCount) * 100) : 0;
        $rejectedPercentage = $totalCount > 0 ? round(($rejectedCount / $totalCount) * 100) : 0;

        // Get last 30 days data for graphs
        $pendingTrend = $this->getReportTrend('pending');
        $approvedTrend = $this->getReportTrend('approved');
        $rejectedTrend = $this->getReportTrend('rejected');

        return view('pages/dashboards.index', compact(
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'pendingPercentage',
            'approvedPercentage',
            'rejectedPercentage',
            'pendingTrend',
            'approvedTrend',
            'rejectedTrend'
        ));
    }

    private function getReportTrend($status)
    {
        $days = 10; // Last 10 days
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            
            $query = Report::whereDate('created_at', $date);
            
            if ($status === 'approved') {
                $query->where('status', 'approved');
            } elseif ($status === 'rejected') {
                $query->where('status', 'rejected');
            } else {
                $query->where('status', 'pending');
            }
            
            $count = $query->count();
            $data[] = $count;
        }

        return $data;
    }
}
