@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')
@section('title', 'Create Test')
@section('vendor-script')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
@endsection
@section('content')
    @if (Session::has('status'))
      <input type="hidden" name="" id="tostStatus" value="{{ Session::get('status') }}">
      <input type="hidden" name="" id="tostMessage" value="{{ Session::get('message') }}">
      <input type="hidden" name="" id="tostType" value="{{ Session::get('status') == 'Success' ? 'text-success' : 'text-warning' }}">

      {{ Session::forget('status') }}
      {{ Session::forget('message') }}
    @endif
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Test/</span>
        Create
    </h4>

    <div class="row">
        <div class="col-12">
            <div class="card">
              <h5 class="card-header">Create Test</h5>
              <form id="createTestForm" method="post"  class="form-repeater">
                @csrf
                <div class="row px-4">
                  <div class="col-sm-3 mb-3">
                    <label for="nameExLarge" class="form-label">Select Board</label>
                    <select name="board" id="board" class="select2 form-select" onchange="getStudents(this.value)" required>
                      <option value="">Select Board</option>
                      @foreach ($boards as $board)
                        <option value="{{ $board->id }}">{{ $board->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-sm-3 mb-3">
                    <label for="nameExLarge" class="form-label">Select Class</label>
                    <select name="class" id="class" class="select2 form-select" onchange="getStudents(this.value)" required>
                      <option value="">Select Class</option>
                      @foreach ($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-sm-3 mb-3">
                    <label for="nameExLarge" class="form-label">Select Book</label>
                    <select name="book" id="book" class="select2 form-select" onchange="getChapters(this.value)" required>
                      <option value="">Select Book</option>
                      @foreach ($books as $book)
                        <option value="{{ $book['id'] }}">{{ $book['name'] }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-sm-3 mb-3">
                    <label for="nameExLarge" class="form-label">Total Questions</label>
                    <input type="input" class="form-control" name="totalQuestions" placeholder="Enter Total Questions" required value="10">
                  </div>
                  <div class="col-sm-3 mb-3">
                    <label for="nameExLarge" class="form-label">Time For Each Question</label>
                    <select name="questionTime" id="questionTime" class="form-select" required>
                      {!! $timeOptions !!}
                    </select>
                  </div>
                  <div class="col-sm-3 mb-3">
                    <label for="nameExLarge" class="form-label">Select Students</label>
                    <select id="students" name="students[]" class="select2 form-select" multiple  >
                    </select>
                  </div>
                  <div class="col-sm-3 mb-3">
                    <label for="nameExLarge" class="form-label">Test Date</label>
                    <input type="date" name="testDate" id="testDate" value="{{ date("Y-m-d") }}" class="form-control">
                  </div>
                </div>
                <div class="row px-4 mt-3 mb-5" id="chaptersRow">
                  <h6>Please select book</h6>
                </div>
                <hr>
                <div class="px-4" id="manual-questions-btn-show" >
                  <button class="btn btn-primary" onclick="showManualQuestions()" type="button" >
                      <i class="ti ti-plus me-1"></i>
                      <span class="align-middle"> Manual Questions</span>
                  </button>
              </div>
                <div data-repeater-list="questions" class="p-4" id="manual-questions" style="display: none">
                  <div data-repeater-item>
                      <div
                          class="card-header d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                          <h5 class="card-title"> Multiple Choice</h5>
                          <button class="btn btn-label-danger" data-repeater-delete>
                              <i class="ti ti-x ti-xs me-1"></i>
                              <span class="align-middle">Delete</span>
                          </button>
                      </div>
                      <div class="row">
                          <div class="mb-3 col-lg-12 col-xl-12 col-12 mb-0">
                              <label class="form-label" for="form-repeater-1-1">Question</label>
                              {{-- <input type="text" name="description" id="form-repeater-1-1" class="form-control" /> --}}
                              <textarea class="form-control summernote" name="description" id="form-repeater-1-1" rows="2" class="form-control"></textarea>
                          </div>
                      </div>
                      <div class="row">
                          <div class="mb-3 col-lg-6 col-xl-6 col-12 mb-0">
                              <label class="form-label" for="form-repeater-1-2">Option A</label>
                              <textarea class="form-control summernote" name="option-a" id="form-repeater-1-2" rows="2" class="form-control"></textarea>
                              {{-- <input type="text" name="option-a" id="form-repeater-1-2" class="form-control" /> --}}
                          </div>
                          <div class="mb-3 col-lg-6 col-xl-6 col-12 mb-0">
                              <label class="form-label" for="form-repeater-1-3">Option B</label>
                              <textarea class="form-control summernote" name="option-b" id="form-repeater-1-3" rows="2" class="form-control"></textarea>
                              {{-- <input type="text" name="option-b" id="form-repeater-1-3" class="form-control" /> --}}
                          </div>
                          <div class="mb-3 col-lg-6 col-xl-6 col-12 mb-0">
                              <label class="form-label" for="form-repeater-1-4">Option C</label>
                              <textarea class="form-control summernote" name="option-c" id="form-repeater-1-4" rows="2"
                                  class="form-control"></textarea>
                              {{-- <input type="text" name="option-c" id="form-repeater-1-4" class="form-control" /> --}}
                          </div>
                          <div class="mb-3 col-lg-6 col-xl-6 col-12 mb-0">
                              <label class="form-label" for="form-repeater-1-5">Option D</label>
                              <textarea class="form-control summernote" name="option-d" id="form-repeater-1-5" rows="2"
                                  class="form-control"></textarea>
                              {{-- <input type="text" name="option-d" id="form-repeater-1-5" class="form-control" /> --}}
                          </div>
                          <div class="mb-3 col-lg-6 col-xl-6 col-12 mb-0">
                              <label class="form-label">Correct Option</label>
                              <select id="correct-option" name="correct-option" class=" form-select"
                                  data-allow-clear="true">
                                  <option value="a">Option A</option>
                                  <option value="b">Option B</option>
                                  <option value="c">Option C</option>
                                  <option value="d">Option D</option>
                              </select>
                          </div>
                          <div class="mb-12 col-lg-12 col-xl-12 col-12 mb-0">
                              <label class="form-label" for="autosize-demo">Reason</label>
                              <textarea id="autosize-demo" name="answer" rows="3" class="form-control summernote"></textarea>
                          </div>
                      </div>
                      <hr>
                  </div>
              </div>
              <?php $index = 0; ?>
              <div class="px-4" id="manual-questions-btn" style="display: none">
                  <button class="btn btn-primary" type="button" data-repeater-create>
                      <i class="ti ti-plus me-1"></i>
                      <span class="align-middle">Add</span>
                  </button>
              </div>
                <div class="row">
                  <div class="col-12 px-4 mb-4">
                    <button type="button" style="float:right" disabled id="submitTest"  class="btn btn-primary">Create</button>
                  </div>
                </div>
              </form>
            </div>
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
                $('.summernote').summernote({
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

            function resetFormRepeater() {
                var formRepeater = $('#questionForm .form-repeater');

                if (formRepeater.length) {
                    formRepeater.repeater('reset');
                    alert("im")
                }
                formRepeater.repeater('reset');
                alert("out")
            }

      });

      function getChapters(bookId){
        let board_id = $("#board").val();
        let class_id = $("#class").val();
        console.log(class_id);
        $.ajax({
              url: '{{ route('teacher/test/chapters') }}',
              method: 'get',
              data: {
                book_id:bookId,
                board_id:board_id,
                class_id:class_id,
              },
              success: function(response) {
                console.log(response)
                $("#chaptersRow").html(response);
                getStudents();
              }
          });
      }

        // Select All checkbox
        function selectCheckboxes(isChecked){
          var isChecked = $('#select-all').prop('checked');
          if (isChecked == 1) {
            $("#submitTest").removeAttr('disabled');
          }else{
            $("#submitTest").attr('disabled',true);
          }
          $('.checkboxes').prop('checked', isChecked);
        }

        function selectCheckbox() {
          if ($('.checkboxes:checked').length === $('.checkboxes').length) {
            $('#select-all').prop('checked', true);
          } else {
            $('#select-all').prop('checked', false);
          }

          if ($('.checkboxes:checked').length > 0) {
            $("#submit").removeAttr('disabled');
          }else{
            $("#submit").attr('disabled',true);
          }
        }

        $(document).ready(function() {
          var status = $("#tostStatus").val();
          if (status) {
            var message = $("#tostMessage").val();
            showNotification(status,message);
          }
        });

        function showNotification(status,message){
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

        function getStudents(){
        let book_id = $("#book").val();
        let board_id = $("#board").val();
        let class_id = $("#class").val();
          $.ajax({
              url: '{{ route('teacher/test/students') }}',
              method: 'get',
              data: {
                board_id:board_id,
                class_id:class_id,
                book_id:book_id,
              },
              success: function(response) {
                $("#students").html(response.students);
                if (response.students == '<option value="">Select Students</option>') {
                  showNotification('error','Students not found against this board and class');
                }
              }
          });
        }

        function showManualQuestions(){
          $("#manual-questions").show()
          $("#manual-questions-btn").show()
          $("#manual-questions-btn-show").hide()
        }

        $('#submitTest').click(function() {
            $("#submitTest").attr('disabled',true);
            const toastAnimationExample = document.querySelector('.toast-ex');
            var form = $('#createTestForm');
            var formData = form.serialize();

            $.ajax({
                url: '{{ route('teacher/store/test') }}',
                method: 'POST',
                data: formData,
                success: function(response) {

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
                    $("#submitTest").attr('disabled',false);
                },
                error: function(xhr, status, error) {
                  $("#submitTest").attr('disabled',false);
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
    </script>
@endsection
