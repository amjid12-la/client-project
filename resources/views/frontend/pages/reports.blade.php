<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports Library</title>
    <link href="{{ asset('frontend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/custom.css') }}" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Report System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('reports.index') }}">Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white ms-2" href="{{ route('reports.create') }}">Submit Report</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="mb-3">Reports Library</h1>
                <p class="text-muted">Browse all approved reports submitted by the community.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($reports->isEmpty())
            <div class="text-center py-5">
                <h3 class="text-muted">No Reports Available</h3>
                <p class="text-muted">Be the first to submit a report!</p>
                <a href="{{ route('reports.create') }}" class="btn btn-primary mt-3">Submit Report</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Photo</th>
                            <th>Individual Name</th>
                            <th>Location</th>
                            <th>Date</th>
                            <th>Narrative</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                            <tr>
                                <td>
                                    @if($report->photo_path)
                                        <img src="{{ $report->photo_url }}" alt="Report Photo" 
                                             class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                        <span class="text-muted">No photo</span>
                                    @endif
                                </td>
                                <td>{{ $report->individual_name ?? 'N/A' }}</td>
                                <td>{{ $report->location ?? 'N/A' }}</td>
                                <td>{{ $report->incident_date ? $report->incident_date->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    <div style="max-width: 300px;">
                                        {{ Str::limit($report->narrative ?? 'No narrative provided', 100) }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <footer class="bg-light py-4 mt-5">
        <div class="container text-center">
            <p class="text-muted mb-0">&copy; {{ date('Y') }} Report System. All rights reserved.</p>
        </div>
    </footer>

    <script src="{{ asset('frontend/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
