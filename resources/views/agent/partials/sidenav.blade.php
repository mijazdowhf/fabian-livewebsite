@php
    $sideBarLinks = json_decode($sidenav);
@endphp

<div class="sidebar bg--dark">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="{{route('agent.dashboard')}}" class="sidebar__main-logo"><img src="{{siteLogo('dark')}}" alt="image"></a>
        </div>
        <div class="sidebar__menu-wrapper">
            <ul class="sidebar__menu">
                @foreach($sideBarLinks as $key => $data)
                    @if(@$data->submenu)
                        <li class="sidebar-menu-item sidebar-dropdown">
                            <a href="javascript:void(0)" class="{{ menuActive(@$data->menu_active, 3) }}">
                                <i class="menu-icon {{ @$data->icon }}"></i>
                                <span class="menu-title">{{ __(@$data->title) }}</span>
                            </a>
                            <div class="sidebar-submenu {{ menuActive(@$data->menu_active, 2) }} ">
                                <ul>
                                    @foreach($data->submenu as $menu)
                                        <li class="sidebar-menu-item {{ menuActive(@$menu->menu_active) }} ">
                                            <a href="{{ route(@$menu->route_name) }}" class="nav-link">
                                                <i class="menu-icon las la-dot-circle"></i>
                                                <span class="menu-title">{{ __($menu->title) }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    @else
                        <li class="sidebar-menu-item {{ menuActive(@$data->menu_active) }}">
                            <a href="{{ route(@$data->route_name) }}" class="nav-link ">
                                <i class="menu-icon {{ $data->icon }}"></i>
                                <span class="menu-title">{{ __(@$data->title) }}</span>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
        <div class="version-info text-center text-uppercase">
            <span class="text--primary">{{__(systemDetails()['name'])}}</span>
            <span class="text--success">@lang('V'){{systemDetails()['version']}} </span>
        </div>
    </div>
</div>
@push('script')
    <script>
        if($('li').hasClass('active')){
            $('#sidebar__menuWrapper').animate({
                scrollTop: eval($(".active").offset().top - 320)
            },500);
        }
    </script>
@endpush
