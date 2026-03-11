<div class="menu-item pt-5">
    <div class="menu-content">
        <span class="menu-heading fw-bold text-uppercase fs-7">Reports</span>
    </div>
</div>
<div class="menu menu-column menu-rounded menu-sub-indention px-3 fw-semibold fs-6" id="#kt_app_sidebar_menu"
    data-kt-menu="true" data-kt-menu-expand="false">

    <!--begin:Dashboard-->
    <div class="menu-item">
        <a class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <span class="menu-icon">{!! getIcon('element-11', 'fs-2') !!}</span>
            <span class="menu-title">Dashboard</span>
        </a>
    </div>
    <!--end:Dashboard-->

    <!--begin:Site Content Management-->
    <div class="menu-item">
        <a class="menu-link {{ request()->routeIs('admin.site-content.*') ? 'active' : '' }}" 
           href="{{ route('admin.site-content.index') }}">
            <span class="menu-icon">{!! getIcon('setting-2', 'fs-2') !!}</span>
            <span class="menu-title">Site Content</span>
        </a>
    </div>
    <!--end:Site Content Management-->

    <!--begin:Report Management-->
    <div data-kt-menu-trigger="click"
        data-kt-menu-placement="right-start"
        class="menu-item menu-accordion {{ request()->routeIs('admin.reports.*') ? 'here show' : '' }}">
        <!--begin:Menu link-->
        <span class="menu-link">
            <span class="menu-icon">{!! getIcon('document', 'fs-2') !!}</span>
            <span class="menu-title">Report Management</span>
            <span class="menu-arrow"></span>
        </span>
        <!--end:Menu link-->

        <div class="menu-sub menu-sub-accordion">
            <div class="menu-item">
                <a class="menu-link {{ request()->routeIs('admin.reports.index') ? 'active' : '' }}"
                    href="{{ route('admin.reports.index') }}">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Pending Reports</span>
                    @php
                        $pendingCount = \App\Models\Report::pending()->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="badge badge-warning ms-auto">{{ $pendingCount }}</span>
                    @endif
                </a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ request()->routeIs('admin.reports.approved') ? 'active' : '' }}"
                    href="{{ route('admin.reports.approved') }}">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Approved Reports</span>
                </a>
            </div>
            <div class="menu-item">
                <a class="menu-link {{ request()->routeIs('admin.reports.archive') ? 'active' : '' }}"
                    href="{{ route('admin.reports.archive') }}">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Archive</span>
                </a>
            </div>
        </div>
    </div>
    <!--end:Report Management-->

    <!--begin:Logged Users-->
    <div class="menu-item">
        <a class="menu-link {{ request()->routeIs('admin.logged-users.*') ? 'active' : '' }}" 
           href="{{ route('admin.logged-users.index') }}">
            <span class="menu-icon">{!! getIcon('profile-user', 'fs-2') !!}</span>
            <span class="menu-title">Logged Users</span>
        </a>
    </div>
    <!--end:Logged Users-->

    <!--begin:View Site-->
    <div class="menu-item pt-5">
        <div class="menu-content">
            <span class="menu-heading fw-bold text-uppercase fs-7">Website</span>
        </div>
    </div>
    <div class="menu-item">
        <a class="menu-link" href="{{ route('home') }}">
            <span class="menu-icon">{!! getIcon('eye', 'fs-2') !!}</span>
            <span class="menu-title">View Site</span>
            <span class="menu-badge">
                <i class="ki-duotone ki-arrow-up-right fs-3 ms-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </span>
        </a>
    </div>
    <!--end:View Site-->
</div>

@push('scripts')
<script>
    // Ensure sidebar menu only opens on click, not hover
    document.addEventListener('DOMContentLoaded', function() {
        const menuAccordion = document.querySelector('[data-kt-menu-trigger="click"]');
        if (menuAccordion) {
            // Remove any hover event listeners
            menuAccordion.removeEventListener('mouseenter', null);
            menuAccordion.removeEventListener('mouseover', null);
        }
    });
</script>
@endpush
