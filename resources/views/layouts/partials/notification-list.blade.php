@foreach($unreadNotifications as $notif)
    <li>
        <a href="{{ $notif->data['link'] }}">
            <div>
                <i class="fa {{ $notif->data['icon'] ?? 'fa-bell' }} {{ $notif->data['color'] ?? 'text-navy' }} fa-fw"></i> 
                <strong>{{ $notif->data['title'] ?? 'SKU Update' }}</strong>
                <span class="pull-right text-muted small">{{ $notif->created_at->diffForHumans() }}</span>
                <br>
                <span class="small text-muted" style="margin-left: 25px;">{{ Str::limit($notif->data['message'], 40) }}</span>
            </div>
        </a>
    </li>
    <li class="divider"></li>
@endforeach