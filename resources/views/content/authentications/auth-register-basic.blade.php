@php
    $customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Register Basic - Pages')

@section('vendor-style')
    <!-- Vendor -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
@endsection

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>
@endsection

@section('page-script')
    {{-- <script src="{{ asset('assets/js/pages-auth.js') }}"></script> --}}
@endsection

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                @if (Session::has('status'))
                    <input type="hidden" name="" id="tostStatus" value="{{ Session::get('status') }}">
                    <input type="hidden" name="" id="tostMessage" value="{{ Session::get('message') }}">
                    <input type="hidden" name="" id="tostType"
                        value="{{ Session::get('status') == 'Success' ? 'text-success' : 'text-warning' }}">

                    {{ Session::forget('status') }}
                    {{ Session::forget('message') }}
                @endif
                <!-- Register Card -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-4 mt-2">
                            <a href="{{ url('/') }}" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">@include('_partials.macros', ['height' => 20, 'withbg' => 'fill: #fff;'])</span>
                                <span
                                    class="app-brand-text demo text-body fw-bold ms-1">{{ config('variables.templateName') }}</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-1 pt-2">Adventure starts here 🚀</h4>
                        <p class="mb-4">Before Start please enter your basic info.</p>

                        <form id="formAuthentication" class="mb-3" action="{{ url('store-pending-user') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" required class="form-control" id="fullName" name="fullName"
                                    placeholder="Enter your fullName" autofocus>
                                <input type="hidden" value="{{ $card_id }}" id="card_id" name="card_id">
                                <input type="hidden" value="{{ $type }}" id="type" name="type">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input required type="email" class="form-control" id="email" name="email"
                                    placeholder="Enter your email" autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">User name</label>
                                <input required type="text" class="form-control" id="username" name="username"
                                    placeholder="Enter your username" autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input required type="password" id="password" class="form-control" name="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" />
                            </div>

                            @if ($type == 'Student')
                                <div class="mb-3">
                                    <label class="form-label" for="board_id">Your Board?</label>
                                    <select required id="board_id" name="board_id" class="select2 form-select"
                                        data-allow-clear="true">
                                        <option value="">Select</option>
                                        @foreach ($boards as $board)
                                            <option value="{{ $board->id }}">{{ $board->name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="class_id">Your Class?</label>
                                    <select required id="class_id" name="class_id" class="select2 form-select"
                                        data-allow-clear="true">
                                        <option value="">Select</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            @endif

                            <div class="mb-3">
                                <label for="user-image" class="form-label">Image</label>
                                <input class="form-control" name="user-image" type="file" id="user-image">
                            </div>
                            <div class="mb-4">
                                <div class="form-check">
                                    <input required class="form-check-input" type="checkbox" id="terms-conditions"
                                        name="terms">
                                    <label class="form-check-label" for="terms-conditions">
                                        I agree to that i fill the correct information.
                                        {{-- <a href="javascript:void(0);">privacy policy & terms</a> --}}
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary d-grid w-100">
                                Let me in
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Register Card -->
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var status = $("#tostStatus").val();
            if (status) {
                var message = $("#tostMessage").val();
                showNotification(status, message);
            }

        });

        function showNotification(status, message) {
            const toastAnimationExample = document.querySelector('.toast-ex');
            $('.toast-ex .fw-semibold').text(status);
            $('.toast-ex .toast-body').text(message);

            // Show the toast notification
            selectedType = $("#tostType").val();
            selectedAnimation = "animate__fade";
            toastAnimationExample.classList.add(selectedAnimation);
            toastAnimationExample.querySelector('.ti').classList.add(selectedType);
            toastAnimation = new bootstrap.Toast(toastAnimationExample);
            toastAnimation.show();
        }
    </script>
@endsection
