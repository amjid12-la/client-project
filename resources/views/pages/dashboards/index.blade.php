<x-default-layout>

    @section('title')
        Dashboard
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('dashboard') }}
    @endsection

    <div class="row g-4 g-xl-6">
        <!-- Left Side - Single Circular Chart with All Reports -->
        <div class="col-xl-3">
            <div class="card card-flush h-100">
                <div class="card-body d-flex flex-column justify-content-center text-center py-6">
                    <div class="mb-4">
                        <canvas id="reportsCircle" width="180" height="180"></canvas>
                    </div>
                    <div class="fs-6 fw-bold text-gray-800 mb-3">Reports Overview</div>
                    
                    <!-- Legend -->
                    <div class="d-flex flex-column gap-2 text-start px-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 12px; height: 12px; background: #FFC700; border-radius: 2px;"></div>
                                <span class="fs-8 text-gray-700">Pending</span>
                            </div>
                            <span class="fs-8 fw-bold text-gray-800">{{ $pendingCount }} ({{ $pendingPercentage }}%)</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 12px; height: 12px; background: #50CD89; border-radius: 2px;"></div>
                                <span class="fs-8 text-gray-700">Approved</span>
                            </div>
                            <span class="fs-8 fw-bold text-gray-800">{{ $approvedCount }} ({{ $approvedPercentage }}%)</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 12px; height: 12px; background: #F1416C; border-radius: 2px;"></div>
                                <span class="fs-8 text-gray-700">Rejected</span>
                            </div>
                            <span class="fs-8 fw-bold text-gray-800">{{ $rejectedCount }} ({{ $rejectedPercentage }}%)</span>
                        </div>
                    </div>
                    
                    <div class="fs-9 text-gray-600 mt-4">
                        Total: {{ $approvedCount + $rejectedCount + $pendingCount }} Reports
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Line Graphs -->
        <div class="col-xl-9">
            <!-- Pending Reports Graph -->
            <div class="card card-flush mb-4 mb-xl-6">
                <div class="card-header pt-4 pb-2 min-h-auto">
                    <h3 class="card-title align-items-start flex-column">
                        <a href="{{ route('admin.reports.index') }}" class="card-label fw-bold text-gray-800 fs-7 text-hover-primary text-decoration-none">
                            Pending Reports
                        </a>
                        <span class="text-gray-600 mt-1 fw-semibold fs-9">Last 10 days</span>
                    </h3>
                    <div class="card-toolbar">
                        <span class="fs-3 fw-bold text-warning">{{ $pendingCount }}</span>
                    </div>
                </div>
                <div class="card-body pt-1 pb-3">
                    <canvas id="pendingChart" height="45"></canvas>
                </div>
            </div>

            <!-- Approved Reports Graph -->
            <div class="card card-flush mb-4 mb-xl-6">
                <div class="card-header pt-4 pb-2 min-h-auto">
                    <h3 class="card-title align-items-start flex-column">
                        <a href="{{ route('admin.reports.approved') }}" class="card-label fw-bold text-gray-800 fs-7 text-hover-primary text-decoration-none">
                            Approved Reports
                        </a>
                        <span class="text-gray-600 mt-1 fw-semibold fs-9">Last 10 days</span>
                    </h3>
                    <div class="card-toolbar">
                        <span class="fs-3 fw-bold text-success">{{ $approvedCount }}</span>
                    </div>
                </div>
                <div class="card-body pt-1 pb-3">
                    <canvas id="approvedChart" height="45"></canvas>
                </div>
            </div>

            <!-- Rejected Reports Graph -->
            <div class="card card-flush">
                <div class="card-header pt-4 pb-2 min-h-auto">
                    <h3 class="card-title align-items-start flex-column">
                        <a href="{{ route('admin.reports.archive') }}" class="card-label fw-bold text-gray-800 fs-7 text-hover-primary text-decoration-none">
                            Rejected Reports
                        </a>
                        <span class="text-gray-600 mt-1 fw-semibold fs-9">Last 10 days</span>
                    </h3>
                    <div class="card-toolbar">
                        <span class="fs-3 fw-bold text-danger">{{ $rejectedCount }}</span>
                    </div>
                </div>
                <div class="card-body pt-1 pb-3">
                    <canvas id="rejectedChart" height="45"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Single Circle Chart with All Reports
            const reportsCircleCtx = document.getElementById('reportsCircle').getContext('2d');
            new Chart(reportsCircleCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Approved', 'Rejected'],
                    datasets: [{
                        data: [{{ $pendingCount }}, {{ $approvedCount }}, {{ $rejectedCount }}],
                        backgroundColor: ['#FFC700', '#50CD89', '#F1416C'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    cutout: '70%',
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            enabled: true,
                            backgroundColor: '#fff',
                            titleColor: '#181C32',
                            bodyColor: '#181C32',
                            borderColor: '#E4E6EF',
                            borderWidth: 1,
                            padding: 10,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += context.parsed + ' reports';
                                    return label;
                                }
                            }
                        }
                    }
                }
            });

            // Common chart options
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleColor: '#181C32',
                        bodyColor: '#181C32',
                        borderColor: '#E4E6EF',
                        borderWidth: 1,
                        padding: 6,
                        displayColors: false,
                        titleFont: { size: 11 },
                        bodyFont: { size: 10 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            stepSize: 1,
                            color: '#7E8299',
                            font: { size: 9 }
                        },
                        grid: {
                            color: '#F1F1F2'
                        }
                    },
                    x: {
                        ticks: { 
                            color: '#7E8299',
                            font: { size: 9 }
                        },
                        grid: { display: false }
                    }
                }
            };

            // Pending Reports Line Chart
            const pendingChartCtx = document.getElementById('pendingChart').getContext('2d');
            new Chart(pendingChartCtx, {
                type: 'line',
                data: {
                    labels: Array.from({length: 10}, (_, i) => {
                        const date = new Date();
                        date.setDate(date.getDate() - (9 - i));
                        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                    }),
                    datasets: [{
                        data: {!! json_encode($pendingTrend) !!},
                        borderColor: '#FFC700',
                        backgroundColor: 'rgba(255, 199, 0, 0.1)',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: '#FFC700',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 1,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: commonOptions
            });

            // Approved Reports Line Chart
            const approvedChartCtx = document.getElementById('approvedChart').getContext('2d');
            new Chart(approvedChartCtx, {
                type: 'line',
                data: {
                    labels: Array.from({length: 10}, (_, i) => {
                        const date = new Date();
                        date.setDate(date.getDate() - (9 - i));
                        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                    }),
                    datasets: [{
                        data: {!! json_encode($approvedTrend) !!},
                        borderColor: '#50CD89',
                        backgroundColor: 'rgba(80, 205, 137, 0.1)',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: '#50CD89',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 1,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: commonOptions
            });

            // Rejected Reports Line Chart
            const rejectedChartCtx = document.getElementById('rejectedChart').getContext('2d');
            new Chart(rejectedChartCtx, {
                type: 'line',
                data: {
                    labels: Array.from({length: 10}, (_, i) => {
                        const date = new Date();
                        date.setDate(date.getDate() - (9 - i));
                        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                    }),
                    datasets: [{
                        data: {!! json_encode($rejectedTrend) !!},
                        borderColor: '#F1416C',
                        backgroundColor: 'rgba(241, 65, 108, 0.1)',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: '#F1416C',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 1,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: commonOptions
            });
        });
    </script>
    @endpush

</x-default-layout>
