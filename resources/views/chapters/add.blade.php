@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')
@section('title', 'Add Chapters')
@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Home/Chapters/</span>
        Add Chapters
    </h4>

    <form id="chapterForm" class="form-repeater">
        <div class="row">
            @csrf
            <div class="col-12">
                <div class="card">
                    <div
                        class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                        <h5 class="card-title mb-sm-0 me-2">Add Chapter</h5>
                        <div class="action-btns">
                            <a href="{{ route('chapter.index') }}" class="btn btn-label-primary me-3">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md">
                                <label class="form-label" for="board_id">Board</label>
                                <select id="board_id" name="board_id" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                    @foreach ($boards as $board)
                                        <option value="{{ $board->id }}">{{ $board->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md">
                                <label class="form-label" for="class_id">Class</label>
                                <select id="class_id" name="class_id" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md">
                                <label class="form-label" for="book_id">Book</label>
                                <select id="book_id" name="book_id" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                    @foreach ($books as $book)
                                        <option value="{{ $book->id }}">{{ $book->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div
                            class="card-header sticky-element  d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                            <h5 class="card-title mb-sm-0 me-2"></h5>
                            <div class="action-btns">
                                <button type="button" id="submitChapter" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
        <br>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div data-repeater-list="chapters">
                        <div data-repeater-item>
                            <div
                                class="card-header d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                                <h5 class="card-title"> Chapter</h5>
                                <button class="btn btn-label-danger" data-repeater-delete>
                                    <i class="ti ti-x ti-xs me-1"></i>
                                    <span class="align-middle">Delete</span>
                                </button>
                            </div>
                            <div class="row">
                                <div class="mb-12 col-lg-6 col-xl-6 col-6 mb-0">
                                    <label class="form-label" for="chapter_no">Chapter Number</label>
                                    <textarea id="chapter_no" required name="chapter_no" rows="3" class="form-control"></textarea>
                                </div>
                                <div class="mb-12 col-lg-6 col-xl-6 col-6 mb-0">
                                    <label class="form-label" for="name">Chapter Name</label>
                                    <textarea id="name" name="name" required rows="3" class="form-control" autocomplete="off"></textarea>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                    <div class="mb-0">
                        <button class="btn btn-primary" data-repeater-create>
                            <i class="ti ti-plus me-1"></i>
                            <span class="align-middle">Add</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- /Form Repeater -->


@endsection

@section('page2-script')
    <script>
        $(document).ready(function() {

            $('#submitChapter').click(function() {
                const toastAnimationExample = document.querySelector('.toast-ex');
                var form = $('#chapterForm');
                var formData = form.serialize();

                $.ajax({
                    url: '{{ route('chapter.store') }}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#chapterForm [data-repeater-item]').remove();
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
