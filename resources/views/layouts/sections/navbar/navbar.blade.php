@php
    $containerNav = $containerNav ?? 'container-fluid';
    $navbarDetached = $navbarDetached ?? '';
@endphp

<!-- Navbar -->
@if (isset($navbarDetached) && $navbarDetached == 'navbar-detached')
    <nav class="layout-navbar {{ $containerNav }} navbar navbar-expand-xl {{ $navbarDetached }} align-items-center bg-navbar-theme"
        id="layout-navbar">
@endif
@if (isset($navbarDetached) && $navbarDetached == '')
    <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
        <div class="{{ $containerNav }}">
@endif

<!--  Brand demo (display only for navbar-full and hide on below xl) -->
@if (isset($navbarFull))
    <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
        <a href="{{ url('/') }}" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">
                @include('_partials.macros', ['height' => 20])
            </span>
            <span class="app-brand-text demo menu-text fw-bold">{{ config('variables.templateName') }}</span>
        </a>
    </div>
@endif

<!-- ! Not required for layout-without-menu -->
@if (!isset($navbarHideToggle))
    <div
        class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>
@endif

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

    <!-- Style Switcher -->
    <div class="navbar-nav align-items-center">
        <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
            <i class='ti ti-sm'></i>
        </a>
    </div>
    <!--/ Style Switcher -->

    <ul class="navbar-nav flex-row align-items-center ms-auto">

        <!-- User -->
        <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                <div class="avatar avatar-online">

                    @if (Auth::user()->image == null)
                        <img src="{{ asset('assets/img/avatars/1112.png') }}" alt class="w-px-40 h-auto rounded-circle">
                    @else
                        <img src="files/userImages/{{ Auth::user()->image }}" alt class="w-px-40 h-auto rounded-circle">
                    @endif
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item"
                        href="{{ Route::has('profile.show') ? route('profile.show') : 'javascript:void(0);' }}">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-online">

                                    @if (Auth::user()->image == null)
                                        <img src="{{ asset('assets/img/avatars/1112.png') }}" alt
                                            class="w-px-40 h-auto rounded-circle">
                                    @else
                                        <img src="files/userImages/{{ Auth::user()->image }}" alt
                                            class="w-px-40 h-auto rounded-circle">
                                    @endif


                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-semibold d-block">
                                    {{ Auth::user()->name }}
                                </span>
                                <small class="text-muted">{{ Auth::user()->role->role }}</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ url('/edit-user') }}">
                        <i class="ti ti-user-check me-2 ti-sm"></i>
                        <span class="align-middle">My Profile</span>
                    </a>
                </li>

                <li>
                    <div class="dropdown-divider"></div>
                </li>
                @if (Auth::user()->role_id == 4)
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="menu-icon tf-icons ti ti-clipboard-text"></i>
                            <span class="align-middle">{{ Auth::user()->board->name ?? '' }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="menu-icon tf-icons ti ti-file-certificate"></i>
                            <span class="align-middle">{{ Auth::user()->class->name ?? '' }} Class</span>
                        </a>
                    </li>
                @endif
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}">
                        <i class='ti ti-logout me-2'></i>
                        <span class="align-middle">Logout</span>
                    </a>
                </li>
            </ul>
            <!--/ User -->
    </ul>
</div>

@if (!isset($navbarDetached))
    </div>
@endif
</nav>
<!-- / Navbar -->
