@extends('layouts/layoutMaster')
@section('title', 'Create Test')
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
              <form id="createTestForm" method="post" action="{{ url('teacher/store/test') }}">
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
                <div class="row">
                  <div class="col-12 px-4 mb-4">
                    <button type="submit" style="float:right" disabled id="submit"  class="btn btn-primary">Create</button>
                  </div>
                </div>
              </form>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script>

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
            $("#submit").removeAttr('disabled');
          }else{
            $("#submit").attr('disabled',true);
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
    </script>
@endsection
