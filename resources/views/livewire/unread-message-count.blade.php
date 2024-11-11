<span class="inline-flex items-center pl-1">
    @if($unreadCount > 0)
        <i class="fas fa-bell text-red-500 animate-bounce mr-1"></i>
        <span class="text-red-500 font-bold">({{ $unreadCount }})</span>
    @endif
</span>
