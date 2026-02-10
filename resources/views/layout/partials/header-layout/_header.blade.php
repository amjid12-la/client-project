<!--begin::Header-->
<div id="kt_app_header" class="app-header">
	<!--begin::Header container-->
	<div class="app-container container-xxl d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
		<!--begin::Logo-->
		<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-15">
			<a href="{{ route('home') }}" class="text-decoration-none">
				<span class="fw-bold fs-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; letter-spacing: 1px;">
					Report System
				</span>
			</a>
		</div>
		<!--end::Logo-->
		<!--begin::Header wrapper-->
		<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
			@include(config('settings.KT_THEME_LAYOUT_DIR').'/partials/header-layout/header/_menu/_menu')
			@include(config('settings.KT_THEME_LAYOUT_DIR').'/partials/header-layout/header/_navbar')
		</div>
		<!--end::Header wrapper-->
	</div>
	<!--end::Header container-->
</div>
<!--end::Header-->
