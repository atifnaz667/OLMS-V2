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
        <div class="card">
            <h5 class="card-header">Form Repeater</h5>
            <div class="card-body">
                <form class="form-repeater">
                    <div data-repeater-list="group-a">
                        <div data-repeater-item>
                            <div class="row">
                                <div class="mb-3 col-lg-6 col-xl-2 col-12 mb-0">
                                    <label class="form-label" for="form-repeater-1-3">Question Type</label>
                                    <select id="form-repeater-1-3" class="form-select">
                                        <option value="long">Long</option>
                                        <option value="short">Short</option>
                                        <option value="mcq">Mcq</option>
                                    </select>
                                </div>

                                <div class="mb-3 col-lg-6 col-xl-6 col-12 mb-0">
                                    <label class="form-label" for="form-repeater-1-1">Question</label>
                                    <input type="text" id="form-repeater-1-1" class="form-control"
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
                                    <textarea id="autosize-demo" rows="3" class="form-control"></textarea>
                                </div>
                                <div class="mb-3 col-lg-6 col-xl-6 col-12 mb-0 mcq-options">
                                    <!-- Options -->
                                </div>
                                <div class="mb-12 col-lg-12 col-xl-12 col-12 mb-0 reason-field">
                                    <!-- Reason Field -->
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
                </form>
            </div>
        </div>
    </div>
    <!-- /Form Repeater -->


@endsection


@section('script')
    <script>

      $(document).ready(function(){
      })
    </script>
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
@endsection

