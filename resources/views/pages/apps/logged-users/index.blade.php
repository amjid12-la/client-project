<x-default-layout>

    @section('title')
        Logged Users
    @endsection

    @section('breadcrumbs')
        <ul class="breadcrumb fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">User Management</li>
            <li class="breadcrumb-item text-dark">Logged Users</li>
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
                <h3 class="fw-bold m-0">Logged Users ({{ $totalUsers }})</h3>
            </div>
            <div class="card-toolbar">
                <!-- Search Bar -->
                <div class="d-flex align-items-center">
                    <form action="{{ route('admin.logged-users.index') }}" method="GET" class="position-relative">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-3 mt-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" name="search" id="users-search" class="form-control form-control-sm ps-10 pe-10" placeholder="Search users..." style="min-width: 250px;" value="{{ $search ?? '' }}">
                        @if($search)
                            <a href="{{ route('admin.logged-users.index') }}" class="btn btn-sm btn-icon position-absolute end-0 top-50 translate-middle-y me-1">
                                <i class="ki-duotone ki-cross fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body py-4">
            @if($users->isEmpty())
                <div class="text-center py-10">
                    <div class="mb-5">
                        {!! getIcon('information-5', 'fs-5x text-muted') !!}
                    </div>
                    <h3 class="text-muted">No Users Found</h3>
                    <p class="text-muted">No users match your search criteria.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="logged-users-table">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-50px">#</th>
                                <th class="min-w-200px">Full Name</th>
                                <th class="min-w-200px">Email</th>
                                <th class="min-w-125px">Registered</th>
                                <th class="text-end min-w-100px">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @foreach($users as $user)
                                <tr>
                                    <td class="text-center fw-bold">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                <div class="symbol-label">
                                                    <div class="symbol-label fs-3 bg-light-primary text-primary">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 fw-bold">{{ $user->name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-gray-800">{{ $user->email }}</span>
                                    </td>
                                    <td data-order="{{ $user->created_at->timestamp }}">
                                        <span class="badge badge-light-primary">{{ $user->created_at->format('M d, Y') }}</span>
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-light btn-active-light-primary view-user-btn"
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->name }}"
                                            data-user-email="{{ $user->email }}"
                                            data-user-registered="{{ $user->created_at->format('F d, Y h:i A') }}"
                                            data-user-initial="{{ strtoupper(substr($user->name, 0, 1)) }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#userDetailsModal">
                                            {!! getIcon('eye', 'fs-3') !!}
                                            View
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-5">
                    <div class="text-muted">
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                    </div>
                    <div>
                        {{ $users->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- User Details Modal -->
    <div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 400px;">
            <div class="modal-content" style="background: #2d2d2d; color: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                <div class="modal-body p-0">
                    <!-- User Avatar Section -->
                    <div class="text-center py-5 position-relative" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px 12px 0 0;">
                        <!-- Close Button -->
                        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                        
                        <div class="symbol symbol-circle symbol-100px overflow-hidden mx-auto mb-3" style="border: 4px solid rgba(255,255,255,0.3);">
                            <div class="symbol-label">
                                <div class="symbol-label fs-1 bg-white text-primary" id="modal-user-initial">
                                    U
                                </div>
                            </div>
                        </div>
                        <h4 class="text-white mb-1 fw-bold" id="modal-user-name">User Name</h4>
                        <p class="text-white-50 mb-0 small" id="modal-user-email">user@example.com</p>
                    </div>

                    <!-- User Details Section -->
                    <div class="p-4">
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-user fs-4 me-3" style="color: #667eea; width: 24px;"></i>
                                <div class="flex-grow-1">
                                    <div class="text-white-50 small">Full Name</div>
                                    <div class="text-white fw-semibold" id="modal-user-name-detail">User Name</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-envelope fs-4 me-3" style="color: #667eea; width: 24px;"></i>
                                <div class="flex-grow-1">
                                    <div class="text-white-50 small">Email Address</div>
                                    <div class="text-white fw-semibold" id="modal-user-email-detail">user@example.com</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-calendar-alt fs-4 me-3" style="color: #667eea; width: 24px;"></i>
                                <div class="flex-grow-1">
                                    <div class="text-white-50 small">Registered</div>
                                    <div class="text-white fw-semibold" id="modal-user-registered">January 1, 2024</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="p-4 pt-0">
                        <button type="button" class="btn btn-light w-100" data-bs-dismiss="modal" style="border-radius: 8px;">
                            <i class="fas fa-times me-2"></i>Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-submit search on input
        document.getElementById('users-search').addEventListener('input', function() {
            clearTimeout(window.searchTimeout);
            window.searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });

        // Handle View Button Click
        document.addEventListener('click', function(e) {
            if (e.target.closest('.view-user-btn')) {
                const button = e.target.closest('.view-user-btn');
                const userName = button.getAttribute('data-user-name');
                const userEmail = button.getAttribute('data-user-email');
                const userRegistered = button.getAttribute('data-user-registered');
                const userInitial = button.getAttribute('data-user-initial');

                // Update modal content
                document.getElementById('modal-user-initial').textContent = userInitial;
                document.getElementById('modal-user-name').textContent = userName;
                document.getElementById('modal-user-email').textContent = userEmail;
                document.getElementById('modal-user-name-detail').textContent = userName;
                document.getElementById('modal-user-email-detail').textContent = userEmail;
                document.getElementById('modal-user-registered').textContent = userRegistered;
            }
        });
    </script>
    @endpush

    @push('styles')
    <style>
        /* Custom Pagination Styling */
        .pagination {
            display: flex;
            padding-left: 0;
            list-style: none;
            border-radius: 0.25rem;
        }

        .page-item {
            margin: 0 2px;
        }

        .page-link {
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-left: 0;
            line-height: 1.25;
            color: #3b82f6;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .page-link:hover {
            z-index: 2;
            color: #2563eb;
            background-color: #e7f3ff;
            border-color: #3b82f6;
        }

        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #3b82f6;
            border-color: #3b82f6;
        }

        .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            cursor: not-allowed;
            background-color: #fff;
            border-color: #dee2e6;
            opacity: 0.5;
        }

        .page-link:focus {
            z-index: 3;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }
    </style>
    @endpush

</x-default-layout>
