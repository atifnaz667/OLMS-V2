@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')
@section('title', 'Multiple Choices')
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
        Multiple Choices
    </h4>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div
                    class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                    <h5 class="card-title mb-sm-0 me-2">Listing</h5>
                    <div class="action-btns">
                        <a href="{{ route('add-mcq-choice') }}" class="btn btn-primary">Add Record</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md">
                            <label class="form-label" for="board_id">Board</label>
                            <select id="board_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                                @foreach ($boards as $board)
                                    <option value="{{ $board->id }}">{{ $board->name }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md">
                            <label class="form-label" for="class_id">Class</label>
                            <select id="class_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md">
                            <label class="form-label" for="book_id">Book</label>
                            <select id="book_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md">
                            <label class="form-label" for="chapter_id">Chapter</label>
                            <select id="chapter_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                            </select>
                        </div>
                        <div class="col-md">
                            <label class="form-label" for="topic_id">Topic</label>
                            <select id="topic_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>

                    <div
                        class="card-header sticky-element  d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                        <h5 class="card-title mb-sm-0 me-2"></h5>
                        <div class="action-btns">
                            <button type="button" onclick="fetchQuestionRecords()" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </div>
                <hr>
                {{-- <div class="col-md-1">
                  <label class="form-label" for="state">Records per Page</label>
                    <select id="perPageSelect" class="select2 form-select" data-allow-clear="true">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                      </select>
                    </div> --}}

                {{-- table  --}}
                <div class="row m-3">
                    <div class="col-md">
                        <input type="text" id="search-input"class="form-control" placeholder="Search MCQs">
                    </div>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Sr#</th>
                                <th>Type</th>
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
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasViewQuestion"
        aria-labelledby="offcanvasViewQuestionLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasViewQuestionLabel" class="offcanvas-title">View Mcq</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0">
            <form class="update-class pt-0" id="viewQuestionForm">
                @csrf
                <div class="mb-12 col-lg-12 col-xl-12 col-12 mb-0">
                    <label class="form-label" for="update-question">Question</label>
                    <textarea required id="update-question" name="update-question" rows="3" class="form-control update-question"></textarea>
                </div>

                <div class="row">
                    <div class="mb-3">
                        <label class="form-label" for="form-repeater-1-1">Option A</label>
                        <textarea class="form-control" name="option-a" id="form-repeater-1-1" rows="2" class="form-control"></textarea>
                        {{-- <input type="text" name="option-a" id="form-repeater-1-1" class="form-control" /> --}}
                        <input type="hidden" name="option-a-id" id="option-a-id" class="form-control" />
                        <input type="hidden" name="questionId" id="questionId" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="form-repeater-1-2">Option B</label>
                        <textarea class="form-control" name="option-b" id="form-repeater-1-2" rows="2" class="form-control"></textarea>
                        {{-- <input type="text" name="option-b" id="form-repeater-1-2" class="form-control" /> --}}
                        <input type="hidden" name="option-b-id" id="option-b-id" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="form-repeater-1-3">Option C</label>
                        <textarea class="form-control" name="option-c" id="form-repeater-1-3" rows="2" class="form-control"></textarea>
                        {{-- <input type="text" name="option-c" id="form-repeater-1-3" class="form-control" /> --}}
                        <input type="hidden" name="option-c-id" id="option-c-id" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="form-repeater-1-4">Option D</label>
                        <textarea class="form-control" name="option-d" id="form-repeater-1-4" rows="2" class="form-control"></textarea>
                        {{-- <input type="text" name="option-d" id="form-repeater-1-4" class="form-control" /> --}}
                        <input type="hidden" name="option-d-id" id="option-d-id" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correct Option</label>
                        <select id="correct-option" name="correct-option" class=" form-select" data-allow-clear="true">
                            <option value="a">Option A</option>
                            <option value="b">Option b</option>
                            <option value="c">Option C</option>
                            <option value="d">Option D</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="form-repeater-1-2">Reason</label>
                        <textarea id="autosize-demo" name="answer" rows="3" class="form-control update-question"></textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Update</button>
                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
            </form>
        </div>
    </div>
@endsection

@section('page2-script')
    <script>
        $(document).ready(function() {
            initializeSummernote();
            $('button[data-repeater-create]').click(function() {
                setTimeout(function() {
                    initializeSummernote();
                }, 100); // Delay the initialization to ensure the DOM is updated
            });

            function initializeSummernote() {
                $('.update-question').summernote({
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
            }
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
        var offcanvasElementview = document.getElementById('offcanvasViewQuestion');
        var offcanvas = new bootstrap.Offcanvas(offcanvasElementview);

        function viewQuestion(id) {
            $('#update-question').val('');
            $('input[name^="option-"]').val('');
            $('#correct-option').empty();
            $('textarea[name="answer"]').val('');
            $.ajax({
                url: "{{ route('mcq-choice.show', '') }}" + "/" + id,
                type: 'GET',
                success: function(response) {
                    // Update the form fields with the fetched data
                    $('#update-question').summernote('code', response.Question.description);
                    $('#autosize-demo').summernote('code', response.Question.mcq_choices.find(function(choice) {
                        return choice.is_true === 1;
                    }).reason);
                    // $('#update-question').val(response.Question.description);
                    $('#questionId').val(response.Question.id);

                    // Loop through the mcq choices and populate the options
                    $.each(response.Question.mcq_choices, function(index, choice) {
                        var optionName = 'option-' + String.fromCharCode(97 + index);
                        var textareaElement = $('textarea[name="' + optionName + '"]');
                        var inputIdElement = $('input[name="' + optionName + '-id"]');
                        var isTrue = choice.is_true === 1;
                        textareaElement.summernote('code', choice.choice);

                        // textareaElement.val(choice.choice); // Set the value of the textarea
                        inputIdElement.val(choice.id);

                        if (isTrue) {
                            $('#correct-option').prepend('<option value="' + optionName +
                                '" selected>' + choice.choice + '</option>');
                        } else {
                            $('#correct-option').append('<option value="' + optionName + '">' + choice
                                .choice + '</option>');
                        }
                    });
                    // Populate the reason field with the correct option's reason
                    // $('textarea[name="answer"]').val(response.Question.mcq_choices.find(function(choice) {
                    //     return choice.is_true === 1;
                    // }).reason);

                    // Show the offcanvas
                    offcanvas.show();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    // Handle error if necessary
                }
            });
        }

        function updateQuestion(id) {
            // Get the form data
            var _token = $('input[name="_token"]').val();
            var question = $('#update-question').val();
            var optionA = $('textarea[name="option-a"]').val();
            var optionAId = $('input[name="option-a-id"]').val();
            var optionB = $('textarea[name="option-b"]').val();
            var optionBId = $('input[name="option-b-id"]').val();
            var optionC = $('textarea[name="option-c"]').val();
            var optionCId = $('input[name="option-c-id"]').val();
            var optionD = $('textarea[name="option-d"]').val();
            var optionDId = $('input[name="option-d-id"]').val();
            var correctOption = $('#correct-option').val();
            var reason = $('textarea[name="answer"]').val();

            var formData = {
                _token: _token,
                question: question,
                'option-a': optionA,
                'option-a-id': optionAId,
                'option-b': optionB,
                'option-b-id': optionBId,
                'option-c': optionC,
                'option-c-id': optionCId,
                'option-d': optionD,
                'option-d-id': optionDId,
                'correct-option': correctOption,
                answer: reason
            };
            $.ajax({
                url: "{{ route('mcq-choice.update', '') }}" + "/" + id,
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
                    offcanvas.hide();

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

        // Handle the form submission
        $('#viewQuestionForm').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            var questionId = $('#questionId').val();
            updateQuestion(questionId);
        });

        function fetchQuestionRecords(page = 1) {
            var topicId = $('#topic_id').val();
            var check = "ajax";
            var searchQuery = $('#search-input').val();
            $.ajax({
                url: '{{ route('mcq-choice.index') }}',
                method: 'GET',
                data: {
                    topic_id: topicId,
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
                                    '<td>' + question.description + '</td>' +
                                    "<td>" +
                                    "<a onclick=\"viewQuestion('" + question
                                    .id +
                                    "')\" class=\"btn-icon edit-record\"data-id='" + question
                                    .id +
                                    "'><i class=\"ti ti-edit\"></i></a>" +
                                    "<a class=\"btn-icon delete-question\" data-id='" + question.id +
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
                        url: "{{ route('mcq-choice.destroy', '') }}" + "/" + user_id,
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
                                title: status,
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
        $('#filterButton, #perPageSelect').on('click change', function(e) {
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

        // Update pagination UI
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
    </script>
@endsection
