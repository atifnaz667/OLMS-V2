<!DOCTYPE html>
<html lang="{{ session()->get('locale') ?? app()->getLocale() }}"
    class="{{ $configData['style'] }}-style {{ $navbarFixed ?? '' }} layout-menu-offcanvas  layout-menu-collapsed {{ $footerFixed ?? '' }} {{ $customizerHidden ?? '' }}"
    dir="{{ $configData['textDirection'] }}" data-theme="{{ $configData['theme'] }}"
    data-assets-path="{{ asset('/assets') . '/' }}" data-base-url="{{ url('/') }}" data-framework="laravel"
    data-template="{{ $configData['layout'] . '-menu-' . $configData['theme'] . '-' . $configData['style'] }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('title')
    </title>
    <meta name="description"
        content="{{ config('variables.templateDescription') ? config('variables.templateDescription') : '' }}" />
    <meta name="keywords"
        content="{{ config('variables.templateKeyword') ? config('variables.templateKeyword') : '' }}">
    <!-- laravel CRUD token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Canonical SEO -->
    <link rel="canonical" href="{{ config('variables.productPage') ? config('variables.productPage') : '' }}">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <style>
        .quick-access {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 999;
        }

        .quick-access-btn {
            background-color: #7367f0;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 24px;
            cursor: pointer;
            outline: none;
        }

        .quick-access-options {
            display: none;
            position: absolute;
            bottom: 70px;
            right: 0;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            white-space: nowrap;
            width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .quick-access-options button {
            display: block;
            width: 100%;
            border: none;
            background: none;
            padding: 10px 0;
            cursor: pointer;
            text-align: left;
            font-size: 16px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .quick-access-options button:hover {
            background-color: #f0f0f0;
        }
    </style>

    <!-- Include Styles -->
    @include('layouts/sections/styles')

    <!-- Include Scripts for customizer, helper, analytics, config -->
    @include('layouts/sections/scriptsIncludes')
</head>

<body>
    @if (Auth::user())
        @if (Auth::user()->role_id == 4)
            <div class="quick-access">
                <button id="quick-access-btn" class="quick-access-btn" data-bs-toggle="modal" data-bs-target="#calculatorModal"><i class="fa-solid fa-calculator"></i></button>

            </div>
        @endif
    @endif
    <!-- Modal -->
    <div class="modal fade" id="calculatorModal" tabindex="-1" aria-labelledby="calculatorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-fullscreen" id="modalClass">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="calculatorModalLabel">Calculator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <iframe src="https://www.desmos.com/scientific"
                        style="width: 100%; height: 500px; border: none;"></iframe>
                </div>
            </div>
        </div>
    </div>
    <div class="bs-toast toast toast-ex animate__animated my-2" role="alert" aria-live="assertive" aria-atomic="true"
        data-bs-delay="2000">
        <div class="toast-header">
            <i class="ti ti-bell ti-xs me-2"></i>
            <div class="me-auto fw-semibold">Error</div>
            <small class="text-muted"></small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            There is some error
        </div>
    </div>
    <!-- Layout Content -->
    @yield('layoutContent')
    <!--/ Layout Content -->

    <script>
        document.getElementById("quick-access-btn").addEventListener("click", function(event) {
            var options = document.querySelector(".quick-access-options");
            options.style.display = options.style.display === "none" || options.style.display === "" ? "block" :
                "none";
            event.stopPropagation();
        });

        // Add a click event listener to the document body to hide the options when anywhere outside the options is clicked
        document.body.addEventListener("click", function() {
            var options = document.querySelector(".quick-access-options");
            options.style.display = "none";
        });

        function handleOptionClick(option) {
            alert("Selected Option: " + option);
            var options = document.querySelector(".quick-access-options");
            options.style.display = "none";
        }
    </script>

    <!-- Include Scripts -->
    @include('layouts/sections/scripts')

    <script>
        $(document).ready(function () {
            changeModalClass(); // Call it when the document is ready.

            $(window).resize(function () {
                changeModalClass(); // Call it when the window is resized.
            });
        });
        function changeModalClass(){
            let width = $(document).width(); // Get the width of the document
            if (width < 797) {
                $("#modalClass").removeClass('modal-lg');
                $("#modalClass").addClass('modal-fullscreen');
                $("#modalBody").addClass('p-1');
                $("#modalBody").removeClass('modal-body');
            } else {
                $("#modalClass").addClass('modal-lg');
                $("#modalClass").removeClass('modal-fullscreen');
                $("#modalBody").removeClass('p-1');
                $("#modalBody").addClass('modal-body');
            }
        }
    </script>
    @yield('script')
</body>

</html>
