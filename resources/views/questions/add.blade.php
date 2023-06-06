@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')


@section('title', 'Chapters')


@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/jquery-sticky/jquery-sticky.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/autosize/autosize.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
    <script src="{{ asset('assets/js/forms-extras.js') }}"></script>
@endsection

<style>
    .pagination-nav {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
        margin-right: 20px;
    }

    .mcq-options,
    .reason-field {
        display: none;
    }
</style>
@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Home/Question/</span>
        Add Question
    </h4>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div
                    class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                    <h5 class="card-title mb-sm-0 me-2">Listing</h5>
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
                            <select id="topic_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>

                    <div
                        class="card-header sticky-element  d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                        <h5 class="card-title mb-sm-0 me-2"></h5>
                        <div class="action-btns">
                            <button type="button" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>
    </div>
    <br>
    <div class="col-12">
        <div class="card border-top border-0 border-4 border-primary">
            <div class="card-body p-4">
                <div class="px-3">
                    <h5 class="card-title">Add New Question</h5>
                    <hr />
                </div>
                <div class="form-body mt-4">
                    <form method="POST" action="{{ route('question.store') }}">
                        @csrf
                        <div id="questions">
                            <div class="question mb-4">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="form-label">Question</label>
                                        <textarea class="form-control" name="questions[][question]"></textarea>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Question Type</label>
                                        <select class="form-select" name="questions[][type]">
                                            <option value="long">Long Answer</option>
                                            <option value="short">Short Answer</option>
                                            <option value="mcq">Multiple Choice</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mcq-options">
                                    <div class="col-sm-6">
                                        <label class="form-label">Option 1</label>
                                        <input type="text" class="form-control" name="questions[][options][0]">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Option 2</label>
                                        <input type="text" class="form-control" name="questions[][options][1]">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Option 3</label>
                                        <input type="text" class="form-control" name="questions[][options][2]">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Option 4</label>
                                        <input type="text" class="form-control" name="questions[][options][3]">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Correct Option</label>
                                        <select class="form-select" name="questions[][correct_option]">
                                            <option value="1">Option 1</option>
                                            <option value="2">Option 2</option>
                                            <option value="3">Option 3</option>
                                            <option value="4">Option 4</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row reason-field">
                                    <div class="col-sm-6">
                                        <label class="form-label">Reason</label>
                                        <input type="text" class="form-control" name="questions[][reason]">
                                    </div>
                                </div>
                                <hr />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-primary" onclick="addQuestion()">
                                    Add Question
                                </button>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Form Repeater -->


@endsection


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    function addQuestion() {
        var questionsContainer = document.getElementById('questions');
        var questionDiv = document.createElement('div');
        questionDiv.classList.add('question', 'mb-4');

        var questionRow = document.createElement('div');
        questionRow.classList.add('row');

        var questionCol1 = document.createElement('div');
        questionCol1.classList.add('col-sm-6');
        var questionLabel = document.createElement('label');
        questionLabel.classList.add('form-label');
        questionLabel.innerHTML = 'Question';
        var questionTextarea = document.createElement('textarea');
        questionTextarea.classList.add('form-control');
        questionTextarea.name = 'questions[][question]';
        questionCol1.appendChild(questionLabel);
        questionCol1.appendChild(questionTextarea);

        var questionCol2 = document.createElement('div');
        questionCol2.classList.add('col-sm-6');
        var typeLabel = document.createElement('label');
        typeLabel.classList.add('form-label');
        typeLabel.innerHTML = 'Question Type';
        var typeSelect = document.createElement('select');
        typeSelect.classList.add('form-select');
        typeSelect.name = 'questions[][type]';
        var longOption = document.createElement('option');
        longOption.value = 'long';
        longOption.innerHTML = 'Long Answer';
        var shortOption = document.createElement('option');
        shortOption.value = 'short';
        shortOption.innerHTML = 'Short Answer';
        var mcqOption = document.createElement('option');
        mcqOption.value = 'mcq';
        mcqOption.innerHTML = 'Multiple Choice';
        typeSelect.appendChild(longOption);
        typeSelect.appendChild(shortOption);
        typeSelect.appendChild(mcqOption);
        questionCol2.appendChild(typeLabel);
        questionCol2.appendChild(typeSelect);

        questionRow.appendChild(questionCol1);
        questionRow.appendChild(questionCol2);
        questionDiv.appendChild(questionRow);

        var optionsRow = document.createElement('div');
        optionsRow.classList.add('row', 'mt-3');

        var optionCol1 = document.createElement('div');
        optionCol1.classList.add('col-sm-6');
        for (var i = 1; i <= 4; i++) {
            var optionLabel = document.createElement('label');
            optionLabel.classList.add('form-label');
            optionLabel.innerHTML = 'Option ' + i;
            var optionInput = document.createElement('input');
            optionInput.type = 'text';
            optionInput.classList.add('form-control');
            optionInput.name = 'questions[][options][' + (i - 1) + ']';
            optionCol1.appendChild(optionLabel);
            optionCol1.appendChild(optionInput);
        }

        var optionCol2 = document.createElement('div');
        optionCol2.classList.add('col-sm-6');
        var correctOptionLabel = document.createElement('label');
        correctOptionLabel.classList.add('form-label');
        correctOptionLabel.innerHTML = 'Correct Option';
        var correctOptionSelect = document.createElement('select');
        correctOptionSelect.classList.add('form-select');
        correctOptionSelect.name = 'questions[][correct_option]';
        for (var j = 1; j <= 4; j++) {
            var option = document.createElement('option');
            option.value = j;
            option.innerHTML = 'Option ' + j;
            correctOptionSelect.appendChild(option);
        }
        optionCol2.appendChild(correctOptionLabel);
        optionCol2.appendChild(correctOptionSelect);

        optionsRow.appendChild(optionCol1);
        optionsRow.appendChild(optionCol2);
        questionDiv.appendChild(optionsRow);

        var reasonRow = document.createElement('div');
        reasonRow.classList.add('row');
        var reasonCol = document.createElement('div');
        reasonCol.classList.add('col-sm-6');
        var reasonLabel = document.createElement('label');
        reasonLabel.classList.add('form-label');
        reasonLabel.innerHTML = 'Reason';
        var reasonInput = document.createElement('input');
        reasonInput.type = 'text';
        reasonInput.classList.add('form-control');
        reasonInput.name = 'questions[][reason]';
        reasonCol.appendChild(reasonLabel);
        reasonCol.appendChild(reasonInput);
        reasonRow.appendChild(reasonCol);
        questionDiv.appendChild(reasonRow);

        var hr = document.createElement('hr');
        questionDiv.appendChild(hr);

        questionsContainer.appendChild(questionDiv);
    }
</script>


<script>
    function toggleMcqOptions() {
        var questionType = $('select[name="questions[][type]"]').val();

        if (questionType === 'mcq') {
            $('.mcq-options').show();
            $('.reason-field').show();
        } else {
            $('.mcq-options').hide();
            $('.reason-field').hide();
        }
    }

    // Initialize visibility based on initial question type
    toggleMcqOptions();

    // Trigger visibility toggle on question type change
    $('select[name="questions[][type]"]').change(function() {
        toggleMcqOptions();
    });
</script>



<script>
    $(document).ready(function() {
        function toggleMcqOptions() {
            var questionType = $('#form-repeater-1-3').val();

            if (questionType === 'mcq') {
                $('.mcq-options').show();
                $('.reason-field').show();
            } else {
                $('.mcq-options').hide();
                $('.reason-field').hide();
            }
        }

        // Initialize visibility based on initial question type
        toggleMcqOptions();

        // Trigger visibility toggle on question type change
        $('#form-repeater-1-3').change(function() {
            toggleMcqOptions();
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
