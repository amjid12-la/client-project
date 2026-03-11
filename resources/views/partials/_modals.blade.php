<!--begin::Modals-->
@include('partials/modals/_upgrade-plan')

@include('partials/modals/create-app/_main')

@include('partials/modals/create-campaign/_main')

@include('partials/modals/create-project/_main')

@include('partials/modals/_new-target')

@include('partials/modals/_view-users')

@include('partials/modals/users-search/_main')

@include('partials/modals/_invite-friends')

<!-- Profile Modal -->
@auth
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" style="position: fixed; top: 70px; right: 20px; margin: 0; max-width: 320px;">
        <div class="modal-content" style="background: #2d2d2d; color: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
            </div>
            <div class="modal-body text-center py-4 px-3">
                <div class="mb-3">
                    @if(Auth::user()->profile_photo_url)
                        <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile Photo" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; background: #7a9aae; font-size: 32px; font-weight: 600; color: #fff;">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                
                <h5 class="mb-2" style="color: #fff; font-weight: 500; font-size: 18px;">{{ Auth::user()->name }}</h5>
                <p class="mb-0" style="color: #a8a8a8; font-size: 14px;">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>
</div>
@endauth
<!--end::Modals-->
