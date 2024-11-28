<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar" id="js-trigger-nav-team">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" id="main-scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav" id="main-sidenav">
            <ul id="sidebarnav" data-modular-id="main_menu_team">

                <!--users[done]-->
                {{-- @if (runtimeGroupMenuVibility([config('visibility.modules.clients'), config('visibility.modules.users')]))
                    <li data-modular-id="main_menu_team_clients"
                        class="sidenav-menu-item {{ $page['mainmenu_customers'] ?? '' }}">
                        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                            <i class="sl-icon-people"></i>
                            <span class="hide-menu">{{ cleanLang(__('lang.customers')) }}
                            </span>
                        </a>
                    </li>
                @endif --}}

                @if (config('visibility.modules.tasks'))
                    <li data-modular-id="main_menu_team_tasks"
                        class="sidenav-menu-item {{ $page['mainmenu_tasks'] ?? '' }} menu-tooltip menu-with-tooltip"
                        title="Category">
                        <a class="waves-effect waves-dark" href="{{ _url('/admin/category_item/') }}"
                            aria-expanded="false" target="_self">
                            <i class="ti-package"></i>
                            <span class="hide-menu">Category</span>
                        </a>
                    </li>
                @endif
                <!--inventory[done]-->
                @if (config('visibility.modules.tasks'))
                    <li data-modular-id="main_menu_team_tasks"
                        class="sidenav-menu-item {{ $page['mainmenu_tasks'] ?? '' }} menu-tooltip menu-with-tooltip"
                        title="Inventory">
                        <a class="waves-effect waves-dark" href="{{ _url('/admin/products/') }}" aria-expanded="false"
                            target="_self">
                            <i class="ti-package"></i>
                            <span class="hide-menu">Inventory</span>
                        </a>
                    </li>
                @endif


                <li data-modular-id="main_menu_team_home"
                    class="sidenav-menu-item vendor menu-tooltip menu-with-tooltip" title="Warehouse">
                    <a class="waves-effect waves-dark" href="{{ _url('warehouse') }}" aria-expanded="false"
                        target="_self">
                        <i class="ti-archive"></i>
                        <span class="hide-menu">Warehouse</span>
                    </a>
                </li>

                <!-- <li data-modular-id="main_menu_team_home"
                    class="sidenav-menu-item vendor menu-tooltip menu-with-tooltip" title="Warehouse">
                    <a class="waves-effect waves-dark" href="{{ _url('warehouse_items') }}" aria-expanded="false" target="_self">
                        <i class="ti-archive"></i>
                        <span class="hide-menu">W Items</span>
                    </a>
                </li> -->

                <!-- Vendor -->

                <li data-modular-id="main_menu_team_home"
                    class="sidenav-menu-item raw-items menu-tooltip menu-with-tooltip" title="Item Info">
                    <a class="waves-effect waves-dark" href="{{ _url('itemsinfo') }}" aria-expanded="false"
                        target="_self">
                        <i class="ti-info-alt"></i>
                        <span class="hide-menu">Items Info</span>
                    </a>
                </li>

                <!-- Vendor -->
                <li data-modular-id="main_menu_team_home"
                    class="sidenav-menu-item purchase menu-tooltip menu-with-tooltip" title="Purchase">
                    <a class="waves-effect waves-dark" href="{{ _url('purchase') }}" aria-expanded="false"
                        target="_self">
                        <i class="ti-shopping-cart"></i>
                        <span class="hide-menu">Purchase</span>
                    </a>
                </li>

                <li data-modular-id="main_menu_team_home"
                    class="sidenav-menu-item raw-items menu-tooltip menu-with-tooltip" title="Raw Items">
                    <a class="waves-effect waves-dark" href="{{ _url('raw-items') }}" aria-expanded="false"
                        target="_self">
                        <i class="ti-home"></i>
                        <span class="hide-menu">Raw Items</span>
                    </a>
                </li>

                <li data-modular-id="main_menu_team_home"
                    class="sidenav-menu-item raw-items menu-tooltip menu-with-tooltip" title="Units">
                    <a class="waves-effect waves-dark" href="{{ _url('units') }}" aria-expanded="false" target="_self">
                        <i class="ti-ruler-pencil"></i>
                        <span class="hide-menu">Units</span>
                    </a>
                </li>



                <li data-modular-id="main_menu_team_home"
                    class="sidenav-menu-item raw-items menu-tooltip menu-with-tooltip" title="Department">
                    <a class="waves-effect waves-dark" href="{{ _url('department') }}" aria-expanded="false"
                        target="_self">
                        <i class="ti-blackboard"></i>
                        <span class="hide-menu">Department</span>
                    </a>
                </li>



                @if (config('visibility.modules.tasks'))
                    <li data-modular-id="main_menu_team_tasks"
                        class="sidenav-menu-item {{ $page['mainmenu_tasks'] ?? '' }} menu-tooltip menu-with-tooltip"
                        title="Manufacture Plan">
                        <a class="waves-effect waves-dark" href="{{ _url('/admin/manufacture_plan/') }}"
                            aria-expanded="false" target="_self">
                            <i class="ti-settings"></i>
                            <span class="hide-menu">M Plan</span>
                        </a>
                    </li>
                @endif

                @if (config('visibility.modules.tasks'))
                    <li data-modular-id="main_menu_team_tasks"
                        class="sidenav-menu-item {{ $page['mainmenu_tasks'] ?? '' }} menu-tooltip menu-with-tooltip"
                        title="Cutting">
                        <a class="waves-effect waves-dark" href="{{ _url('/admin/cutting/') }}"
                            aria-expanded="false" target="_self">
                            <i class="ti-cut"></i>
                            <span class="hide-menu">Cutting</span>
                        </a>
                    </li>
                @endif
                @if (config('visibility.modules.tasks'))
                    <li data-modular-id="main_menu_team_tasks"
                        class="sidenav-menu-item {{ $page['mainmenu_tasks'] ?? '' }} menu-tooltip menu-with-tooltip"
                        title="Printing">
                        <a class="waves-effect waves-dark" href="{{ _url('/admin/printing/') }}"
                            aria-expanded="false" target="_self">
                            <i class="ti-printer"></i>
                            <span class="hide-menu">Printing</span>
                        </a>
                    </li>
                @endif
                @if (config('visibility.modules.tasks'))
                    <li data-modular-id="main_menu_team_tasks"
                        class="sidenav-menu-item {{ $page['mainmenu_tasks'] ?? '' }} menu-tooltip menu-with-tooltip"
                        title="Stiching">
                        <a class="waves-effect waves-dark" href="{{ _url('/admin/stiching/') }}"
                            aria-expanded="false" target="_self">
                            <i class="ti-control-play"></i>
                            <span class="hide-menu">Stiching</span>
                        </a>
                    </li>
                @endif
                @if (config('visibility.modules.tasks'))
                    <li data-modular-id="main_menu_team_tasks"
                        class="sidenav-menu-item {{ $page['mainmenu_tasks'] ?? '' }} menu-tooltip menu-with-tooltip"
                        title="Final">
                        <a class="waves-effect waves-dark" href="{{ _url('/admin/final/') }}" aria-expanded="false"
                            target="_self">
                            <i class="ti-check-box"></i>
                            <span class="hide-menu">Final</span>
                        </a>
                    </li>
                @endif
                <li data-modular-id="main_menu_team_home"
                    class="sidenav-menu-item raw-items menu-tooltip menu-with-tooltip" title="Customers">
                    <a class="waves-effect waves-dark" href="{{ _url('customers') }}" aria-expanded="false"
                        target="_self">
                        <i class="sl-icon-people"></i>
                        <span class="hide-menu">Customers</span>
                    </a>
                </li>
                <li data-modular-id="main_menu_team_home"
                    class="sidenav-menu-item raw-items menu-tooltip menu-with-tooltip" title="Sales">
                    <a class="waves-effect waves-dark" href="{{ _url('sales') }}" aria-expanded="false"
                        target="_self">
                        <i class="sl-icon-people"></i>
                        <span class="hide-menu">Sales</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
