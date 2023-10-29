@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')
@section('vendor-script')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
@endsection
@section('title', 'Add Notes')
<style>
    .pagination-nav {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
        margin-right: 20px;
    }
</style>

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Home/Notes/</span>
        Add Notes
    </h4>

    <form id="questionForm" class="form-repeater">
        <div class="row">
            @csrf
            <div class="col-12">
                <div class="card">
                    <div
                        class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                        <h5 class="card-title mb-sm-0 me-2">Add Notes</h5>
                        <div class="action-btns">
                            <a href="{{ route('notes') }}" class="btn btn-label-primary me-3">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label class="form-label" for="note_name">Name</label>
                                <input class="form-control" type="text" name="note_name" id="note_name">
                            </div>

                            <div class="mb-12 col-lg-12 col-xl-12 col-12 mb-3">
                                <label class="form-label" for="full-editor">Note</label>
                                <textarea class="form-control summernote" name="note" id="note" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11"></div>
                            <div class="col-md-1">
                                <button class="btn btn-primary" id="submitNote" type="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
        <br>
    </form>
    <!-- /Form Repeater -->


@endsection

@section('page2-script')
    <script>
        $(document).ready(function() {
            initializeSummernote();


            function initializeSummernote() {
                $('.summernote').summernote({
                    tabsize: 4,
                    height: 260,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        // ['view', ['fullscreen', 'codeview', 'help']]
                    ]
                });
                $('.summernote-1').summernote({
                    tabsize: 2,
                    height: 100,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        // ['view', ['fullscreen', 'codeview', 'help']]
                    ]
                });
            }

            $('#submitNote').click(function() {
                const toastAnimationExample = document.querySelector('.toast-ex');
                var form = $('#questionForm');
                var formData = form.serialize();

                $.ajax({
                    url: '{{ route('store-note') }}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#note_name').val('');
                        $('.summernote').summernote('code', '');
                        var status = response.status;
                        var message = response.message;
                        $('.toast-ex .fw-semibold').text(status);
                        $('.toast-ex .toast-body').text(message);

                        // Show the toast notification
                        selectedType = "text-success";
                        selectedAnimation = "animate__fade";
                        toastAnimationExample.classList.add(selectedAnimation);
                        toastAnimationExample.querySelector('.ti').classList.add(selectedType);
                        toastAnimation = new bootstrap.Toast(toastAnimationExample);
                        toastAnimation.show();

                    },
                    error: function(xhr, status, error) {
                        var response = JSON.parse(xhr.responseText);
                        var status = response.status;
                        var message = response.message;

                        $('.toast-ex .fw-semibold').text(status);
                        $('.toast-ex .toast-body').text(message);
                        selectedType = "text-warning";
                        selectedAnimation = "animate__fade";
                        toastAnimationExample.classList.add(selectedAnimation);
                        toastAnimationExample.querySelector('.ti').classList.add(selectedType);
                        toastAnimation = new bootstrap.Toast(toastAnimationExample);
                        toastAnimation.show();
                    }
                });
            });
        });
    </script>
@endsection
