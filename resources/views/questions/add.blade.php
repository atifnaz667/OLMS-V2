@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')
@section('title', 'Add Chapters')
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
        <span class="text-muted fw-light">Home/Question/</span>
        Add Question
    </h4>

    <form id="questionForm" class="form-repeater">
        <div class="row">
            @csrf
            <div class="col-12">
                <div class="card">
                    <div
                        class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                        <h5 class="card-title mb-sm-0 me-2">Add Questions</h5>
                        <div class="action-btns">
                            <a href="{{ route('question.index') }}" class="btn btn-label-primary me-3">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md">
                                <label class="form-label" for="state">Board</label>
                                <select id="board_id" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                    @foreach ($boards as $board)
                                        <option value="{{ $board->id }}">{{ $board->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md">
                                <label class="form-label" for="state">Class</label>
                                <select id="class_id" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md">
                                <label class="form-label" for="state">Book</label>
                                <select id="book_id" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                    @foreach ($books as $book)
                                        <option value="{{ $book->id }}">{{ $book->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md">
                                <label class="form-label" for="state">Chapter</label>
                                <select id="chapter_id" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="col-md">
                                <label class="form-label" for="state">Topic</label>
                                <select id="topic_id" name="topic_id" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                </select>
                            </div>
                        </div>

                        <div
                            class="card-header sticky-element  d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                            <h5 class="card-title mb-sm-0 me-2"></h5>
                            <div class="action-btns">
                                <button type="button" id="submitQuestion" class="btn btn-primary">Submit</button>
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
                <h5 class="card-header">Form Repeater</h5>
                <div class="card-body">
                    <div data-repeater-list="questions">
                        <div data-repeater-item>
                            <div class="row">
                                <div class="mb-3 col-lg-6 col-xl-2 col-12 mb-0">
                                    <label class="form-label" for="form-repeater-1-3">Question Type</label>
                                    <select id="form-repeater-1-3" name="question_type" class="form-select">
                                        <option value="long">Long</option>
                                        <option value="short">Short</option>
                                    </select>
                                </div>

                                <div class="mb-3 col-lg-6 col-xl-6 col-12 mb-0">
                                    <label class="form-label" for="form-repeater-1-1">Question</label>
                                    <input type="text" name="description" id="form-repeater-1-1" class="form-control"
                                        placeholder="john.doe" />
                                </div>

                                <div class="mb-3 col-lg-12 col-xl-2 col-12 d-flex align-items-center mb-0">
                                    <button class="btn btn-label-danger mt-4" data-repeater-delete>
                                        <i class="ti ti-x ti-xs me-1"></i>
                                        <span class="align-middle">Delete</span>
                                    </button>
                                </div>
                            </div>
                            <div class="row">

                                <div class="mb-12 col-lg-12 col-xl-12 col-12 mb-0">
                                    <label class="form-label" for="form-repeater-1-2">Answer</label>
                                    <textarea id="autosize-demo" name="answer" rows="3" class="form-control"></textarea>
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
            function resetFormRepeater() {
                var formRepeater = $('#questionForm .form-repeater');

                if (formRepeater.length) {
                    formRepeater.repeater('reset');
                    alert("im")
                }
                formRepeater.repeater('reset');
                alert("out")
            }

            $('#submitQuestion').click(function() {
                const toastAnimationExample = document.querySelector('.toast-ex');
                var form = $('#questionForm');
                var formData = form.serialize();

                $.ajax({
                    url: '{{ route('question.store') }}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#questionForm [data-repeater-item]').remove();
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


            $('#board_id, #book_id, #class_id').change(function() {
                var boardId = $('#board_id').val();
                var bookId = $('#book_id').val();
                var classId = $('#class_id').val();

                $.ajax({
                    url: '{{ route('chapterDropDown') }}',
                    method: 'GET',
                    data: {
                        board: $('#board_id').val(),
                        book: $('#book_id').val(),
                        class: $('#class_id').val()
                    },
                    success: function(response) {
                        var chapterSelect = $('#chapter_id');
                        chapterSelect.empty().append('<option value="">Select</option>');

                        if (response.status === 'success') {
                            var chapters = response.Chapters;
                            if (chapters && chapters.length > 0) {
                                $.each(chapters, function(index, chapter) {
                                    chapterSelect.append('<option value="' + chapter
                                        .id + '">' + chapter.name + '</option>');
                                });
                            }
                        } else {
                            console.error(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });

            });
            $('#board_id, #book_id, #class_id, #chapter_id').change(function() {
                $.ajax({
                    url: '{{ route('topicDropDown') }}',
                    method: 'GET',
                    data: {

                        chapter: $('#chapter_id').val()
                    },
                    success: function(response) {
                        var topicSelect = $('#topic_id');
                        topicSelect.empty().append('<option value="">Select</option>');

                        if (response.status === 'success') {
                            var topics = response.Topics;
                            if (topics && topics.length > 0) {
                                $.each(topics, function(index, topic) {
                                    topicSelect.append('<option value="' + topic
                                        .id + '">' + topic.name + '</option>');
                                });
                            }
                        } else {
                            console.error(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });

            });
        });
    </script>
@endsection
