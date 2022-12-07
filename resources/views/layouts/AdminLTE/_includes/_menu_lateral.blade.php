<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header" style="color:#fff;"> MAIN MENU <i class="fa fa-level-down"></i></li>
            @foreach(App\Models\Menu::orderBy('sort_order','asc')->get() as $menuItem)
                @if( $menuItem->parent_id === 0 )
                    @if( $menuItem->slug === '#' )
                        <li class="treeview {{ in_array(Request::segment(1), $menuItem->segment) && Request::segment(1) !== null ? 'active menu-open' : null }}">
                            <a href="#">
                                <i class="{{ $menuItem->icon }}"></i>
                                <span>{{ $menuItem->menu_title }}</span>
                                <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                            </a>
                    @else
                        <li class="{{ Request::segment(1) === substr($menuItem->slug, 1) ? 'active' : null }}">
                            <a href="{{ url($menuItem->slug) }}" title="{{ $menuItem->menu_title }}">
                                <i class="{{ $menuItem->icon }}"></i>
                                <span> {{ $menuItem->menu_title }}</span>
                            </a>

                            @endif
                            @endif

                            @if( ! $menuItem->children->isEmpty() )
                                <ul class="treeview-menu">
                                    @foreach($menuItem->children as $subMenuItem)
                                        @if(Auth::user()->can('root-dev', ''))
                                            <li class="{{ Request::segment(1) === $subMenuItem->segment[0] && Request::segment(2) === null ? 'active' : null }}">
                                                <a href="{{ url($subMenuItem->slug) }}"
                                                   title="{{ $subMenuItem->menu_title }}">
                                                    <i class="{{ $subMenuItem->icon }}"></i>
                                                    <span>{{ $subMenuItem->menu_title }}</span>
                                                </a>
                                            </li>
                                        @else
                                            @if($subMenuItem->segment[0] !== 'config')
                                                <li class="{{ Request::segment(1) === $subMenuItem->segment[0] && Request::segment(2) === null ? 'active' : null }}">
                                                    <a href="{{ url($subMenuItem->slug) }}"
                                                       title="{{ $subMenuItem->menu_title }}">
                                                        <i class="{{ $subMenuItem->icon }}"></i>
                                                        <span>{{ $subMenuItem->menu_title }}</span>
                                                    </a>
                                                </li>
                                            @endif
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                        @endforeach

                        {{--            <li class="--}}
                        {{--				{{ Request::segment(1) === null ? 'active' : null }}--}}
                        {{--                {{ Request::segment(1) === 'home' ? 'active' : null }}--}}
                        {{--                ">--}}
                        {{--                <a href="{{ route('home') }}" title="Dashboard"><i class="fa fa-dashboard"></i> <span> Dashboard</span></a>--}}
                        {{--            </li>--}}

                        {{--            @if(Request::segment(1) === 'profile')--}}

                        {{--                <li class="{{ Request::segment(1) === 'profile' ? 'active' : null }}">--}}
                        {{--                    <a href="{{ route('profile') }}" title="Profile"><i class="fa fa-user"></i>--}}
                        {{--                        <span> PROFILE</span></a>--}}
                        {{--                </li>--}}

                        {{--            @endif--}}
                        {{--            <li class="treeview--}}
                        {{--				{{ Request::segment(1) === 'config' ? 'active menu-open' : null }}--}}
                        {{--                {{ Request::segment(1) === 'user' ? 'active menu-open' : null }}--}}
                        {{--                {{ Request::segment(1) === 'role' ? 'active menu-open' : null }}--}}
                        {{--                ">--}}
                        {{--                <a href="#">--}}
                        {{--                    <i class="fa fa-gear"></i>--}}
                        {{--                    <span>SETTINGS</span>--}}
                        {{--                    <span class="pull-right-container">--}}
                        {{--						<i class="fa fa-angle-left pull-right"></i>--}}
                        {{--					</span>--}}
                        {{--                </a>--}}
                        {{--                <ul class="treeview-menu">--}}
                        {{--                    @if (Auth::user()->can('root-dev', ''))--}}
                        {{--                        <li class="{{ Request::segment(1) === 'config' && Request::segment(2) === null ? 'active' : null }}">--}}
                        {{--                            <a href="{{ route('config') }}" title="App Config">--}}
                        {{--                                <i class="fa fa-gear"></i> <span> Settings App</span>--}}
                        {{--                            </a>--}}
                        {{--                        </li>--}}
                        {{--                    @endif--}}
                        {{--                    <li class="--}}
                        {{--						{{ Request::segment(1) === 'user' ? 'active' : null }}--}}
                        {{--                    {{ Request::segment(1) === 'role' ? 'active' : null }}--}}
                        {{--                        ">--}}
                        {{--                        <a href="{{ route('user') }}" title="Users">--}}
                        {{--                            <i class="fa fa-user"></i> <span> Users</span>--}}
                        {{--                        </a>--}}
                        {{--                    </li>--}}
                        {{--                </ul>--}}
                        {{--            </li>--}}
        </ul>
    </section>
</aside>
