@php
    $customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Signup ' . config('variables.templateName'))

@section('vendor-style')
    <!-- Vendor -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
@endsection

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">
@endsection

@section('vendor-script')
    {{-- <script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script> --}}
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/pages-auth.js') }}"></script>
@endsection

@section('content')
    <div class="container-xxl">
        @if (Session::has('status'))
            <input type="hidden" name="" id="tostStatus" value="{{ Session::get('status') }}">
            <input type="hidden" name="" id="tostMessage" value="{{ Session::get('message') }}">
            <input type="hidden" name="" id="tostType"
                value="{{ Session::get('status') == 'Success' ? 'text-success' : 'text-warning' }}">

            {{ Session::forget('status') }}
            {{ Session::forget('message') }}
        @endif
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <!-- Login -->
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
                        <h4 class="mb-1 pt-2">Welcome to {{ config('variables.templateName') }}! 👋</h4>
                        <p class="mb-4">Please enter you card number to create an account and start the adventure</p>

                        <form id="formAuthentication" class="mb-3" action="{{ url('signup') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="card_no" class="form-label">{{ $type }} Card number</label>
                                <input type="hidden" name="type" value="{{ $type }}">
                                <input type="text" class="form-control" id="card_no" name="card_no"
                                    placeholder="Enter your card Number" autofocus>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">Sign up</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /Register -->
            </div>
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
