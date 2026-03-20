<x-default-layout>

    @section('title')
        Site Content Management
    @endsection

    @section('breadcrumbs')
        <ul class="breadcrumb fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">Content Management</li>
            <li class="breadcrumb-item text-dark">Site Content</li>
        </ul>
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <a href="javascript:history.back()" class="btn btn-sm btn-light-primary me-3">
                    {!! getIcon('arrow-left', 'fs-3') !!}
                    Back
                </a>
                <h3 class="card-title">Manage Frontend Content</h3>
            </div>
            <div class="card-toolbar">
                <!-- Search Bar -->
                <div class="d-flex align-items-center">
                    <div class="position-relative">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-3 mt-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" id="content-search" class="form-control form-control-sm ps-10 pe-10" placeholder="Search content..." style="min-width: 250px;">
                        <button type="button" id="content-search-clear" class="btn btn-sm btn-icon position-absolute end-0 top-50 translate-middle-y me-1" style="display: none;">
                            <i class="ki-duotone ki-cross fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body py-4">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="site-content-table">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-125px">Section</th>
                            <th class="min-w-200px">Title</th>
                            <th class="min-w-200px">Content Preview</th>
                            <th class="text-end min-w-150px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-semibold">
                        @forelse($contents as $content)
                            <tr>
                                <td>
                                    <span class="badge badge-light-primary fs-7 fw-bold">
                                        {{ ucfirst(str_replace('_', ' ', $content->section)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-gray-800 fw-bold">{{ $content->title ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    @if($content->content)
                                        @php
                                            $cleanContent = strip_tags($content->content);
                                            $cleanContent = html_entity_decode($cleanContent, ENT_QUOTES, 'UTF-8');
                                            $cleanContent = preg_replace('/\s+/', ' ', $cleanContent);
                                            $cleanContent = trim($cleanContent);
                                        @endphp
                                        <span class="text-gray-600">{{ Str::limit($cleanContent, 80) }}</span>
                                    @elseif($content->subtitle)
                                        @php
                                            $cleanSubtitle = strip_tags($content->subtitle);
                                            $cleanSubtitle = html_entity_decode($cleanSubtitle, ENT_QUOTES, 'UTF-8');
                                            $cleanSubtitle = preg_replace('/\s+/', ' ', $cleanSubtitle);
                                            $cleanSubtitle = trim($cleanSubtitle);
                                        @endphp
                                        <span class="text-gray-600">{{ Str::limit($cleanSubtitle, 80) }}</span>
                                    @else
                                        <span class="text-muted">No content</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.site-content.edit', $content->id) }}" 
                                       class="btn btn-sm btn-light btn-active-light-primary me-2">
                                        {!! getIcon('pencil', 'fs-3') !!}
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.site-content.destroy', $content->id) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-light-danger btn-active-danger" 
                                                onclick="return confirm('Are you sure you want to delete this content?')">
                                            {!! getIcon('trash', 'fs-3') !!}
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-10">
                                    <div class="mb-5">
                                        {!! getIcon('information-5', 'fs-5x text-muted') !!}
                                    </div>
                                    <h3 class="text-muted">No Content Found</h3>
                                    <p class="text-muted">Click "Add New Content" to create your first content section.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#site-content-table').DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                order: [[0, 'asc']],
                columnDefs: [
                    { orderable: false, targets: [3] } // Disable sorting for Actions column
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                dom: 'rtip' // Remove default search box, keep table, info, and pagination
            });

            // Custom search functionality
            $('#content-search').on('keyup', function() {
                var searchValue = this.value;
                table.search(searchValue).draw();
                
                // Show/hide clear button
                if (searchValue.length > 0) {
                    $('#content-search-clear').show();
                } else {
                    $('#content-search-clear').hide();
                }
            });

            // Clear search button
            $('#content-search-clear').on('click', function() {
                $('#content-search').val('');
                table.search('').draw();
                $(this).hide();
            });
        });
    </script>
    @endpush

</x-default-layout>
