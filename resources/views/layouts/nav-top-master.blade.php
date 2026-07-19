<div class="row border-bottom">
    <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li>
                <span class="m-r-sm welcome-message" style="font-weight:bold;">{{ auth()->user()->name }}</span>
            </li>
            <li class="dropdown">
                <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                    <i class="fa fa-bell"></i>  
                    {{-- ID notif-badge ini penting buat di-update via AJAX --}}
                    <span id="notif-badge" class="label label-danger">
                        {{ (isset($notificationData['notifications_count']) ? $notificationData['notifications_count'] : 0) + auth()->user()->unreadNotifications->count() }}
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-alerts">
                    {{-- AREA NOTIFIKASI NPP (MANUAL) --}}
                    @isset($notificationData)
                        @if (isset($notificationData['notif_npp_delay_start']) && count($notificationData['notif_npp_delay_start']) != 0)
                            @foreach ($notificationData['notif_npp_delay_start'] as $projectName => $items)
                                @php
                                    $arr_id = [];
                                    foreach ($items as $value) { array_push($arr_id, $value['id']); }
                                @endphp
                                <li>
                                    <a href="{{ route($notificationData['link_delay_npp'], ['id' => implode(",",$arr_id)]) }}">
                                        <div>
                                            <i class="fa fa-clock-o text-warning"></i> {{ $projectName }} Delay Start ({{ count($items) }})
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                            <li class="divider"></li> 
                        @endif

                        @if (isset($notificationData['notif_npp_delay_end']) && count($notificationData['notif_npp_delay_end']) != 0)
                            @foreach ($notificationData['notif_npp_delay_end'] as $projectName => $items)
                                @php
                                    $arr_id = [];
                                    foreach ($items as $value) { array_push($arr_id, $value['id']); }
                                @endphp
                                <li>
                                    <a href="{{ route($notificationData['link_delay_npp'], ['id' => implode(",",$arr_id)]) }}">
                                        <div>
                                            <i class="fa fa-warning text-danger"></i> {{ $projectName }} Delay End ({{ count($items) }})
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                            <li class="divider"></li> 
                        @endif
                    @endisset

                    {{-- AREA NOTIFIKASI SKU (LARAVEL SYSTEM) --}}
                    {{-- ID sku-notif-list ini target update HTML via AJAX --}}
                    <div id="sku-notif-list">
                        @auth
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                @foreach(auth()->user()->unreadNotifications->take(5) as $notif)
                                    <li>
                                        <a href="{{ $notif->data['link'] ?? '#' }}">
                                            <div>
                                                <i class="fa {{ $notif->data['icon'] ?? 'fa-bell' }} {{ $notif->data['color'] ?? 'text-navy' }} fa-fw"></i> 
                                                <strong>{{ $notif->data['title'] ?? 'SKU Update' }}</strong>
                                                <span class="pull-right text-muted small">{{ $notif->created_at->diffForHumans() }}</span>
                                                <br>
                                                <span class="small text-muted" style="margin-left: 25px;">{{ Str::limit($notif->data['message'] ?? '', 40) }}</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                @endforeach
                            @endif
                        @endauth
                    </div>

                    <li>
                        <div class="text-center link-block">
                            <a href="#" onclick="event.preventDefault(); document.getElementById('mark-all-read').submit();">
                                <strong>Mark All as Read</strong> <i class="fa fa-check"></i>
                            </a>
                        </div>
                        <form id="mark-all-read" action="{{ route('notifications.markAllRead') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>

            @auth
            <li>
                <a href="#" style="color: #ed5565; font-weight: bold;" onclick="event.preventDefault(); document.getElementById('logout-form-top').submit();">
                    <i class="fa fa-sign-out"></i>Log out 
                </a>
                <form id="logout-form-top" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
            @endauth
            
            @guest
            <li>
                <a href="{{ route('login.perform') }}">
                    <i class="fa fa-sign-in"></i> Log in
                </a>
            </li>
            @endguest
        </ul>
    </nav>
</div>