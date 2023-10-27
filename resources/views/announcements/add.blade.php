@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')
@section('title', 'Notice Board')
@section('content')
    @if (Session::has('status'))
        <input type="hidden" name="" id="tostStatus" value="{{ Session::get('status') }}">
        <input type="hidden" name="" id="tostMessage" value="{{ Session::get('message') }}">
        <input type="hidden" name="" id="tostType"
            value="{{ Session::get('status') == 'Success' ? 'text-success' : 'text-warning' }}">

        {{ Session::forget('status') }}
        {{ Session::forget('message') }}
    @endif
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Home/</span>
        Announcemenst
    </h4>

    <form action="{{ url('suggestion/store') }}" method="post">
        <div class="row">
            @csrf
            <div class="col-9 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label" for="board_id">Subject</label>
                                <input type="text" name="subject" id="subject" class="form-control" required>
                            </div>
                            <div class="col-12 mt-3">
                                <label class="form-label" for="class_id">Message</label>
                                <textarea name="message" class="form-control" id="message" cols="30" rows="10" required></textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button style="float:right" type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <br>
    </form>
    <!-- /Form Repeater -->


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
