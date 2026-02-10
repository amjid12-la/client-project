<!--begin::Logo-->
<div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
	<!--begin::Logo text-->
	<a href="{{ route('home') }}" class="text-decoration-none">
		<span class="app-sidebar-logo-default fw-bold fs-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; letter-spacing: 1px;">
			Report System
		</span>
		<span class="app-sidebar-logo-minimize fw-bold fs-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
			RS
		</span>
	</a>
	<!--end::Logo text-->
	<!--begin::Sidebar toggle-->
	<!--begin::Minimized sidebar setup:
            if (isset($_COOKIE["sidebar_minimize_state"]) && $_COOKIE["sidebar_minimize_state"] === "on") {
                1. "src/js/layout/sidebar.js" adds "sidebar_minimize_state" cookie value to save the sidebar minimize state.
                2. Set data-kt-app-sidebar-minimize="on" attribute for body tag.
                3. Set data-kt-toggle-state="active" attribute to the toggle element with "kt_app_sidebar_toggle" id.
                4. Add "active" class to to sidebar toggle element with "kt_app_sidebar_toggle" id.
            }
        -->
	<div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">{!! getIcon('black-left-line', 'fs-3 rotate-180 ms-1') !!}</div>
	<script type="text/javascript">
		var sidebar_toggle = document.getElementById("kt_app_sidebar_toggle");  // Get the sidebar toggle button element
		@if (isset($_COOKIE["sidebar_minimize_state"]) && $_COOKIE["sidebar_minimize_state"] === "on") 
			document.body.setAttribute("data-kt-app-sidebar-minimize", "on");  // Set the 'data-kt-app-sidebar-minimize' attribute for the body tag
			sidebar_toggle.setAttribute("data-kt-toggle-state", "active");  // Set the 'data-kt-toggle-state' attribute for the sidebar toggle button
			sidebar_toggle.classList.add("active");  // Add the 'active' class to the sidebar toggle button
		@endif

		// Disable hover behavior on sidebar - click only
		document.addEventListener('DOMContentLoaded', function() {
			const sidebar = document.getElementById('kt_app_sidebar');
			const body = document.body;
			
			if (sidebar) {
				// Remove hover class if it exists
				sidebar.classList.remove('drawer-on');
				
				// Prevent hover from opening sidebar when minimized
				body.setAttribute('data-kt-app-sidebar-hoverable', 'false');
				
				// Ensure sidebar only responds to toggle button clicks
				sidebar.addEventListener('mouseenter', function(e) {
					if (body.hasAttribute('data-kt-app-sidebar-minimize')) {
						e.stopPropagation();
					}
				});
			}
		});
	</script>
	<!--end::Sidebar toggle-->
</div>
<!--end::Logo-->
