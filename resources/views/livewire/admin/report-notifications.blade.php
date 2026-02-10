<div>
    <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true">
        <div class="d-flex flex-column bgi-no-repeat rounded-top" style="background-image:url('{{ asset('assets/media/misc/menu-header-bg.jpg') }}')">
            <h3 class="text-white fw-semibold px-9 mt-10 mb-6">
                Report Notifications 
                @if($unreadCount > 0)
                    <span class="badge badge-light-danger ms-2">{{ $unreadCount }}</span>
                @endif
            </h3>
        </div>

        <div class="tab-content">
            <div class="tab-pane fade show active">
                <div class="scroll-y mh-325px my-5 px-8">
                    @forelse($notifications as $notification)
                        <div class="d-flex flex-stack py-4 {{ !$notification->read_at ? 'bg-light-primary rounded px-3' : '' }}">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-35px me-4">
                                    <span class="symbol-label bg-light-warning">
                                        {!! getIcon('notification', 'fs-2 text-warning') !!}
                                    </span>
                                </div>

                                <div class="mb-0 me-2">
                                    <a href="{{ route('admin.reports.show', $notification->data['report_id']) }}" 
                                       class="fs-6 text-gray-800 text-hover-primary fw-bold"
                                       wire:click="markAsRead('{{ $notification->id }}')">
                                        New Report Submitted
                                    </a>
                                    <div class="text-gray-400 fs-7">
                                        {{ $notification->data['individual_name'] ?? 'Unknown' }} - 
                                        {{ $notification->data['location'] ?? 'No location' }}
                                    </div>
                                    <div class="text-gray-400 fs-8">
                                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                    </div>
                                </div>
                            </div>

                            @if(!$notification->read_at)
                                <button wire:click="markAsRead('{{ $notification->id }}')" 
                                        class="btn btn-sm btn-icon btn-active-light-primary">
                                    {!! getIcon('check', 'fs-3') !!}
                                </button>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <div class="text-gray-400">No notifications</div>
                        </div>
                    @endforelse
                </div>

                @if($notifications->isNotEmpty())
                    <div class="py-3 text-center border-top">
                        <button wire:click="markAllAsRead" class="btn btn-color-gray-600 btn-active-color-primary">
                            Mark All as Read
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
