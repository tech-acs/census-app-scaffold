<x-scaffold::round-button title="{{ __('Notifications') }}"  class="inline-flex relative items-center">
    <div wire:poll.3000ms.visible>
        @if ($unreadCount > 0)
            <x-scaffold::icon.bell-unread />
        @else
            <x-scaffold::icon.bell />
        @endif
    </div>
</x-scaffold::round-button>
