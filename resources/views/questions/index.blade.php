@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')
@section('title', 'Questions')
@section('vendor-script')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
@endsection
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
        <span class="text-muted fw-light">Home/</span>
        Question
    </h4>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div
                    class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                    <h5 class="card-title mb-sm-0 me-2">Listing</h5>
                    <div class="action-btns">
                        <a href="{{ route('add-question') }}" class="btn btn-primary">Add Record</a>

                    </div>
                </div>
                <div class="card-body">
                    <div class="row m-3">
                        <div class="col-md-3">
                            <label class="form-label" for="board_id">Board</label>
                            <select id="board_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                                @foreach ($boards as $board)
                                    <option value="{{ $board->id }}">{{ $board->name }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="class_id">Class</label>
                            <select id="class_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="book_id">Book</label>
                            <select id="book_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="chapter_id">Chapter</label>
                            <select id="chapter_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mx-3">
                        <div class="col-md-3">
                            <label class="form-label" for="topic_id">Topic</label>
                            <select id="topic_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="type">Type</label>
                            <select id="type" class="select2 form-select" data-allow-clear="true">
                                <option value="">All</option>
                                @foreach ($questionType as $questionType)
                                    <option value="{{ $questionType->type }}">{{ $questionType->type }}</option>
                                @endforeach
                                {{-- <option value="long">Long</option>
                                <option value="short">Short</option> --}}
                            </select>

                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="nature">Nature</label>
                            <select id="nature" class="select2 form-select" data-allow-clear="true">
                                <option value="">All</option>
                                <option value="exercise">Exercise</option>
                                <option value="conceptual">Conceptual</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="nature">Difficulity Level</label>
                            <select id="difficulty-level" class="select2 form-select" data-allow-clear="true">
                                <option value="">All</option>
                                <option value="Easy">Easy</option>
                                <option value="Medium">Medium</option>
                                <option value="Hard">Hard</option>
                            </select>
                        </div>
                    </div>

                    <div
                        class="card-header d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                        <h5 class="card-title mb-sm-0 me-2"></h5>
                        <div class="action-btns">
                            <button type="button" onclick="fetchQuestionRecords()" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </div>

                <div class="row px-3 pb-3">
                    <div class="col-2">
                        <label class="form-label" for="state">Records per Page</label>
                        <select id="perPageSelect" class=" form-select" data-allow-clear="true">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="col-10">
                        <label class="form-label" for="state">&nbsp;</label>

                        <input type="text" id="search-input" class="form-control" placeholder="Search Question">
                    </div>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Sr#</th>
                                <th>Type</th>
                                <th>Nature</th>
                                <th>Question</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">

                        </tbody>
                    </table>
                </div>
                <nav aria-label="Page navigation" class="pagination-nav">
                    <ul class="pagination"></ul>
                </nav>
            </div>
        </div>
    </div>
    <div class="modal fade" id="largeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form class="update-class pt-0" id="viewQuestionForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel3">View Question</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-6 col-sm-4">
                                <div class="form-group">
                                    <label class="form-label" for="form-repeater-1-3">Question Type</label>
                                    <select id="question_type" required name="question_type" class="form-select">
                                        <option value="long">Long</option>
                                        <option value="short">Short</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6 col-sm-4">
                                <div class="form-group">
                                    <label class="form-label" for="form-repeater-1-4">Question Nature</label>
                                    <select id="question_nature" required name="question_nature" class="form-select">
                                        <option value="Conceptual">Conceptual</option>
                                        <option value="Exercise ">Exercise</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6 col-sm-4">
                                <div class="form-group">
                                    <label class="form-label" for="form-repeater-1-3">Difficulty Level</label>
                                    <select id="difficulty_level" name="difficulty_level" class="form-select">
                                        <option value="Easy">Easy</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Hard">Hard</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <input type="hidden" id="questionId" name="questionId" />
                                <label class="form-label" for="update-question">Question</label>
                                <textarea required id="update-question" name="update-question" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-0">
                                <label class="form-label" for="update-question-answer">Answer</label>
                                <textarea required id="update-question-answer" name="update-question-answer" rows="10" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Save changes</button>
                    </div>
                </div>

        </div>
        </form>
    </div>
@endsection



@section('page2-script')
    <script>
        $(document).ready(function() {

            // $('#search-input').on('input', function() {
            //     fetchQuestionRecords();
            // });

            $('#update-question-answer').summernote({
                // placeholder: 'Hello stand alone ui',
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
            $('#update-question').summernote({
                // placeholder: 'Hello stand alone ui',
                tabsize: 4,
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
    <script>
        var currentPage = 1;
        var lastPage = 1;
        var perPage = 10;
        var numbering = 1;
        const toastAnimationExample = document.querySelector('.toast-ex');

        function viewQuestion(id) {
            $.ajax({
                url: "{{ route('question.show', '') }}" + "/" + id,
                type: 'GET',
                success: function(response) {
                    // Update the form fields with the fetched data
                    // $('#update-question').val(response.Question.description);
                    $('#update-question').summernote('code', response.Question.description);
                    $('#update-question-answer').summernote('code', response.Question.answer.answer);
                    $('#questionId').val(response.Question.id);
                    let question_nature = '<option value="' + response.Question.question_nature + '">' +
                        response.Question.question_nature + '</option>';
                    question_nature +=
                        '<option value="Conceptual">Conceptual</option><option value="Exercise ">Exercise</option>';
                    let difficulty_level = '<option value="' + response.Question.difficulty_level + '">' +
                        response.Question.difficulty_level + '</option>';
                    difficulty_level +=
                        '<option value="Easy">Easy</option><option value="Medium">Medium</option><option value="Hard">Hard</option>';
                    let q_type = response.Question.question_type;
                    let question_type = '<option value="' + response.Question.question_type + '">' + q_type
                        .charAt(0).toUpperCase() + q_type.slice(1) + '</option>';
                    question_type += '<option value="long">Long</option><option value="short">Short</option>';
                    $('#difficulty_level').html(difficulty_level);
                    $('#question_nature').html(question_nature);
                    $('#question_type').html(question_type);
                    $('#questionId').val(response.Question.id);
                    $('#largeModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    // Handle error if necessary
                }
            });
        }

        function updateQuestion(id) {
            var _token = $('input[name="_token"]').val();
            var question = $('#update-question').val();
            var answer = $('#update-question-answer').val();
            var question_nature = $('#question_nature').val();
            var question_type = $('#question_type').val();
            var difficulty_level = $('#difficulty_level').val();
            var formData = {
                question: question,
                _token: _token,
                answer: answer,
                question_type: question_type,
                question_nature: question_nature,
                difficulty_level: difficulty_level,
            };
            $.ajax({
                url: "{{ route('question.update', '') }}" + "/" + id,
                type: 'PUT',
                data: formData,
                success: function(response) {
                    var status = response.status;
                    var message = response.message;
                    $('.toast-ex .fw-semibold').text(status);
                    $('.toast-ex .toast-body').text(message);
                    selectedType = "text-success";
                    selectedAnimation = "animate__fade";
                    toastAnimationExample.classList.add(selectedAnimation);
                    toastAnimationExample.querySelector('.ti').classList.add(selectedType);
                    toastAnimation = new bootstrap.Toast(toastAnimationExample);
                    toastAnimation.show();
                    fetchQuestionRecords(currentPage)
                    $('#largeModal').modal('hide');

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
        }

        $('#viewQuestionForm').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            var questionId = $('#questionId').val();
            updateQuestion(questionId);
        });

        function fetchQuestionRecords(page = 1) {
            var difficulty_level = $('#difficulty-level').val();
            var chapter_id = $('#chapter_id').val();
            var book_id = $('#book_id').val();
            var class_id = $('#class_id').val();
            var type = $('#type').val();
            var topicId = $('#topic_id').val();
            var board_id = $('#board_id').val();
            var nature = $('#nature').val();
            var searchQuery = $('#search-input').val();
            var check = "ajax";
            $.ajax({
                url: '{{ route('question.index') }}',
                method: 'GET',
                data: {
                    difficulty_level: difficulty_level,
                    chapter_id: chapter_id,
                    book_id: book_id,
                    class_id: class_id,
                    board_id: board_id,
                    topic_id: topicId,
                    type: type,
                    nature: nature,
                    check: check,
                    page: page,
                    searchQuery: searchQuery,
                    perPage: perPage
                },
                success: function(response) {
                    var tableBody = $('.table tbody');
                    tableBody.empty();

                    if (response.status === 'success') {
                        var questions = response.data;
                        currentPage = response.current_page;
                        lastPage = response.last_page;
                        numbering = (currentPage - 1) * response.per_page;
                        if (questions && questions.length > 0) {
                            $.each(questions, function(index, question) {
                                var row = '<tr>' +
                                    '<td>' + (++numbering) + '</td>' +
                                    // '<td>' + question.board + '</td>' +
                                    // '<td>' + question.book + '</td>' +
                                    // '<td>' + question.class + '</td>' +
                                    // '<td>' + question.question_no + '</td>' +
                                    '<td>' + question.question_type + '</td>' +
                                    '<td>' + question.question_nature + '</td>' +
                                    '<td>' + question.description + '</td>' +
                                    "<td>" +
                                    "<a onclick=\"viewQuestion('" + question
                                    .id +
                                    "')\" class=\"btn-icon edit-record\"data-id='" + question
                                    .id +
                                    "'><i class=\"ti ti-edit\"></i></a>" +
                                    "<a class=\"btn-icon delete-question\" data-id='" +
                                    question.id +
                                    "'><i class=\"ti ti-trash\"></i></a>" +
                                    "</td>" +
                                    '</tr>';
                                tableBody.append(row);
                            });
                        }
                    } else {
                        console.error(response.message);
                    }

                    updatePaginationUI();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        $(document).on('click', '.delete-question', function() {
            var _token = $('input[name="_token"]').val();
            var user_id = $(this).data('id'),
                dtrModal = $('.dtr-bs-modal.show');
            if (dtrModal.length) {
                dtrModal.modal('hide');
            }
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    confirmButton: 'btn btn-primary me-3',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'DELETE',
                        url: "{{ route('question.destroy', '') }}" + "/" + user_id,
                        data: {
                            _token: _token,
                        },
                        success: function success(response) {
                            fetchQuestionRecords(currentPage)
                            var status = response.status;
                            var message = response.message;
                            Swal.fire({
                                icon: 'success',
                                title: status,
                                text: message,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });
                        },
                        error: function(xhr, status, error) {
                            var response = JSON.parse(xhr.responseText);
                            var status = response.status;
                            var message = response.message;
                            Swal.fire({
                                title: status.charAt(0).toUpperCase() + status.slice(1),
                                text: message,
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });
                        }
                    });
                }
            });
        });

        // Trigger fetchQuestionRecords() on filter button click
        $('#filterButton, #perPageSelect').on('change', function(e) {
            e.preventDefault();
            currentPage = 1; // Reset to first page when filter is applied
            perPage = $('#perPageSelect').val();
            fetchQuestionRecords();
        });

        // Handle pagination click event
        $(document).on('click', '.pagination-link', function(e) {
            e.preventDefault();
            var page = $(this).attr('data-page');
            if (page !== currentPage) {
                fetchQuestionRecords(page);
            }
        });

        function updatePaginationUI() {
            var paginationContainer = $('.pagination');
            paginationContainer.empty();

            if (lastPage > 1) {
                var paginationLinks = '';
                var maxVisiblePages = 5; // Set the maximum number of visible page links

                if (currentPage > 1) {
                    paginationLinks +=
                        '<li class="page-item first"><a class="page-link pagination-link" href="#" data-page="1"><i class="ti ti-chevrons-left ti-xs"></i></a></li>';
                    paginationLinks +=
                        '<li class="page-item prev"><a class="page-link pagination-link" href="#" data-page="' + (
                            currentPage - 1) + '"><i class="ti ti-chevron-left ti-xs"></i></a></li>';
                }

                var startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
                var endPage = Math.min(lastPage, startPage + maxVisiblePages - 1);

                for (var i = startPage; i <= endPage; i++) {
                    var activeClass = (i === currentPage) ? 'active' : '';
                    paginationLinks += '<li class="page-item ' + activeClass +
                        '"><a class="page-link pagination-link" href="#" data-page="' + i + '">' + i + '</a></li>';
                }

                if (currentPage < lastPage) {
                    paginationLinks +=
                        '<li class="page-item next"><a class="page-link pagination-link" href="#" data-page="' + (
                            currentPage + 1) + '"><i class="ti ti-chevron-right ti-xs"></i></a></li>';
                    paginationLinks +=
                        '<li class="page-item last"><a class="page-link pagination-link" href="#" data-page="' + lastPage +
                        '"><i class="ti ti-chevrons-right ti-xs"></i></a></li>';
                }

                paginationContainer.append(paginationLinks);
            }
        }


        // Initial fetch and pagination UI update
        fetchQuestionRecords();

        $("#search-input").keypress(function(e) {

            if (e.which == 13) {
                fetchQuestionRecords();
            }
        });
    </script>
@endsection
