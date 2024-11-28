<header class="topbar">

    <nav class="navbar top-navbar navbar-expand-md navbar-light">

        <div class="navbar-header" id="topnav-logo-container">


            @if (request('dashboard_section') == 'settings')
                <!--exist-->
                <div class="sidenav-menu-item exit-panel m-b-17">
                    <a class="waves-effect waves-dark text-info" href="/home" id="settings-exit-button"
                        aria-expanded="false" target="_self">
                        <i class="sl-icon-logout text-info"></i>
                        <span id="settings-exit-text"
                            class="font-14">{{ str_limit(__('lang.exit_settings'), 20) }}</span>
                    </a>
                </div>
            @else
                <!--logo-->
                <div class="sidenav-menu-item logo m-t-0">
                    <a class="navbar-brand" href="/home">
                        <img src="{{ runtimeLogoSmall() }}" alt="homepage" class="logo-small" />
                        <img src="{{ runtimeLogoLarge() }}" alt="homepage" class="logo-large" />
                    </a>
                </div>
            @endif
        </div>


        <div class="navbar-collapse header-overlay" id="main-top-nav-bar">

            <div class="page-wrapper-overlay js-close-side-panels hidden" data-target=""></div>

            <ul class="navbar-nav mr-auto">

                <!--left menu toogle (hamburger menu) - main application -->
                @if (request('visibility_left_menu_toggle_button') == 'visible')
                    <li class="nav-item main-hamburger-menu">
                        <a class="nav-link nav-toggler hidden-md-up waves-effect waves-dark" href="javascript:void(0)">
                            <i class="sl-icon-menu"></i>
                        </a>
                    </li>
                    <li class="nav-item main-hamburger-menu">
                        <a class="nav-link sidebartoggler hidden-sm-down waves-effect waves-dark update-user-ux-preferences"
                            data-type="leftmenu" data-progress-bar="hidden" data-url=""
                            data-url-temp="{{ url('/') }}/{{ auth()->user()->team_or_contact }}/updatepreferences"
                            data-preference-type="leftmenu" href="javascript:void(0)">
                            <i class="sl-icon-menu"></i>
                        </a>
                    </li>
                @endif


                <!--left menu toogle (hamburger menu) - settings section -->
                @if (request('visibility_settings_left_menu_toggle_button') == 'visible')
                    <li class="nav-item settings-hamburger-menu hidden">
                        <a class="nav-link waves-effect waves-dark js-toggle-settings-menu" href="javascript:void(0)">
                            <i class="sl-icon-menu"></i>
                        </a>
                    </li>
                @endif





                <!--[UPCOMING] search icon-->
                <li class="nav-item hidden-xs-down search-box hidden">
                    <a class="nav-link hidden-sm-down waves-effect waves-dark" href="javascript:void(0)">
                        <i class="icon-Magnifi-Glass2"></i>
                    </a>
                    <form class="app-search">
                        <input type="text" class="form-control" placeholder="Search & enter">
                        <a class="srh-btn">
                            <i class="ti-close"></i>
                        </a>
                    </form>
                </li>
            </ul>


            <!--RIGHT SIDE-->
            <ul class="navbar-nav navbar-top-right my-lg-0" id="right-topnav-navbar">

                <!-- language -->
                @if (config('system.settings_system_language_allow_users_to_change') == 'yes')
                    <li class="nav-item dropdown d-none d-sm-block" id="topnav-language-icon">
                        <a class="nav-link dropdown-toggle p-t-10 waves-effect waves-dark" href="javascript:void(0)"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="sl-icon-globe"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right animated bounceInDown language">
                            <div class="row">
                                @foreach (request('system_languages') as $key => $language)
                                    <div class="col-6">
                                        <a class="dropdown-item js-ajax-request text-capitalize"
                                            href="javascript:void(0)" data-url="{{ url('user/updatelanguage') }}"
                                            data-type="form" data-ajax-type="post"
                                            data-form-id="topNavLangauage{{ $key }}">{{ $language }}
                                        </a>
                                        <span id="topNavLangauage{{ $key }}">
                                            <input type="hidden" name="language" value="{{ $language }}">
                                            <input type="hidden" name="current_url" value="{{ url()->full() }}">
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </li>
                @endif
                <!--language -->


                <!-- profile -->
                <li class="nav-item dropdown u-pro">
                    <a class="nav-link dropdown-toggle p-l-20 p-r-20 waves-dark profile-pic" href="javascript:void(0)"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ auth()->user()->avatar }}" id="topnav_avatar" alt="user" class="" />
                        <span class="hidden-md-down" id="topnav_username">{{ auth()->user()->first_name }}
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right animated flipInY">
                        <ul class="dropdown-user">
                            <li>
                                <div class="dw-user-box">
                                    <div class="u-img"><img src="{{ auth()->user()->avatar }}"
                                            id="topnav_dropdown_avatar" alt="user"></div>
                                    <div class="u-text">
                                        <h4 id="topnav_dropdown_full_name">{{ auth()->user()->first_name }}
                                            {{ auth()->user()->last_name }}</h4>
                                        <p class="text-muted" id="topnav_dropdown_email">{{ auth()->user()->email }}
                                        </p>
                                        <a href="javascript:void(0)"
                                            class="btn btn-rounded btn-danger btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                                            data-toggle="modal" data-target="#commonModal"
                                            data-url="{{ url('/user/avatar') }}"
                                            data-loading-target="commonModalBody" data-modal-size="modal-sm"
                                            data-modal-title="{{ cleanLang(__('lang.update_avatar')) }}"
                                            data-header-visibility="hidden" data-header-extra-close-icon="visible"
                                            data-action-url="{{ url('/user/avatar') }}"
                                            data-action-method="PUT">{{ cleanLang(__('lang.update_avatar')) }}</a>
                                    </div>
                                </div>
                            </li>
                            <li role="separator" class="divider"></li>
                            <!--my profile-->
                            <li>
                                <a href="javascript:void(0)"
                                    class="edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                                    data-toggle="modal" data-target="#commonModal"
                                    data-url="{{ url('/contacts/' . auth()->id() . '/edit?type=profile') }}"
                                    data-loading-target="commonModalBody"
                                    data-modal-title="{{ cleanLang(__('lang.update_my_profile')) }}"
                                    data-action-url="{{ url('/contacts/' . auth()->id()) }}" data-action-method="PUT"
                                    data-action-ajax-class="" data-modal-size="modal-lg"
                                    data-action-ajax-loading-target="team-td-container">
                                    <i class="ti-user p-r-4"></i>
                                    {{ cleanLang(__('lang.update_my_profile')) }}</a>
                            </li>

                            <!--my timesheets-->
                            @if (auth()->user()->is_team && auth()->user()->role->role_timesheets >= 1)
                                <li>
                                    <a href="{{ url('/timesheets/my') }}">
                                        <i class="ti-timer p-r-4"></i>
                                        {{ cleanLang(__('lang.my_time_sheets')) }}</a>
                                </li>
                            @endif

                            @if (auth()->user()->is_client_owner)
                                <!--edit company profile-->
                                <li>
                                    <a href="javascript:void(0)"
                                        class="edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                                        data-toggle="modal" data-target="#commonModal"
                                        data-url="{{ url('/clients/' . auth()->user()->clientid . '/edit') }}"
                                        data-loading-target="commonModalBody"
                                        data-modal-title="{{ cleanLang(__('lang.company_details')) }}"
                                        data-action-url="{{ url('/clients/' . auth()->user()->clientid) }}"
                                        data-action-method="PUT">
                                        <i class="ti-pencil-alt p-r-4"></i>
                                        {{ cleanLang(__('lang.company_details')) }}</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"
                                        class="edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                                        data-toggle="modal" data-target="#commonModal"
                                        data-url="{{ url('/clients/logo') }}" data-loading-target="commonModalBody"
                                        data-modal-size="modal-sm"
                                        data-modal-title="{{ cleanLang(__('lang.update_avatar')) }}"
                                        data-header-visibility="hidden" data-header-extra-close-icon="visible"
                                        data-action-url="{{ url('/clients/logo') }}" data-action-method="PUT">
                                        <i class="ti-pencil-alt p-r-4"></i>
                                        {{ cleanLang(__('lang.company_logo')) }}</a>
                                </li>
                            @endif

                            <!--update notifcations-->
                            <li>
                                <a href="javascript:void(0)" id="topnavUpdateNotificationsButton"
                                    class="edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                                    data-toggle="modal" data-target="#commonModal"
                                    data-url="{{ url('user/updatenotifications') }}"
                                    data-loading-target="commonModalBody"
                                    data-modal-title="{{ cleanLang(__('lang.notification_settings')) }}"
                                    data-action-url="{{ url('user/updatenotifications') }}" data-action-method="PUT"
                                    data-modal-size="modal-lg" data-form-design="form-material"
                                    data-header-visibility="hidden" data-header-extra-close-icon="visible"
                                    data-action-ajax-class="js-ajax-ux-request"
                                    data-action-ajax-loading-target="commonModalBody">
                                    <i class="sl-icon-bell p-r-4"></i>
                                    {{ cleanLang(__('lang.notification_settings')) }}</a>
                            </li>

                            <!--update theme-->
                            <li>
                                <a href="javascript:void(0)" id="topnavUpdatePasswordButton"
                                    class="edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                                    data-toggle="modal" data-target="#commonModal"
                                    data-url="{{ url('user/updatetheme') }}" data-loading-target="commonModalBody"
                                    data-modal-title="{{ cleanLang(__('lang.change_theme')) }}"
                                    data-action-url="{{ url('user/updatetheme') }}" data-action-method="PUT"
                                    data-action-ajax-class="" data-modal-size="modal-sm"
                                    data-form-design="form-material" data-header-visibility="hidden"
                                    data-header-extra-close-icon="visible"
                                    data-action-ajax-loading-target="commonModalBody">
                                    <i class="ti-image p-r-4"></i>
                                    {{ cleanLang(__('lang.change_theme')) }}</a>
                            </li>

                            <!--update password-->
                            <li>
                                <a href="javascript:void(0)" id="topnavUpdatePasswordButton"
                                    class="edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                                    data-toggle="modal" data-target="#commonModal"
                                    data-url="{{ url('user/updatepassword') }}" data-loading-target="commonModalBody"
                                    data-modal-title="{{ cleanLang(__('lang.update_password')) }}"
                                    data-action-url="{{ url('user/updatepassword') }}" data-action-method="PUT"
                                    data-action-ajax-class="" data-modal-size="modal-sm"
                                    data-form-design="form-material" data-header-visibility="hidden"
                                    data-header-extra-close-icon="visible"
                                    data-action-ajax-loading-target="commonModalBody">
                                    <i class="ti-lock p-r-4"></i>
                                    {{ cleanLang(__('lang.update_password')) }}</a>
                            </li>

                            <li role="separator" class="divider"></li>
                            <li>
                                <a href="/logout">
                                    <i class="fa fa-power-off p-r-4"></i> {{ cleanLang(__('lang.logout')) }}</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- /#profile -->
            </ul>
        </div>
    </nav>


</header>
