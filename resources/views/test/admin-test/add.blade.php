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
              <form id="createTestForm" method="post" action="{{ url('admin/store/test') }}">
                @csrf
                <div class="row px-4">
                  <div class="col-sm-3 mb-3">
                    <label for="nameExLarge" class="form-label">Select Board</label>
                    <select name="board" id="board" class="select2 form-select" onchange="getBooks(this.value)" required>
                      <option value="">Select Board</option>
                      @foreach ($boards as $board)
                        <option value="{{ $board->id }}">{{ $board->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-sm-3 mb-3">
                    <label for="nameExLarge" class="form-label">Select Class</label>
                    <select name="class" id="class" class="select2 form-select" onchange="getBooks(this.value)" required>
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
                    <input type="datetime-local" name="testDate" id="testDate" required class="form-control">
                  </div>
                  <div class="col-sm-3 mb-3">
                    <label for="nameExLarge" class="form-label">Expiry Date</label>
                    <input type="datetime-local" name="expiryDate" id="expiryDate" required class="form-control">
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
        $.ajax({
              url: '{{ route('admin/test/chapters') }}',
              method: 'get',
              data: {
                book_id:bookId,
                board_id:board_id,
                class_id:class_id,
              },
              success: function(response) {
                $("#chaptersRow").html(response);
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

        function getBooks(){
          let board_id = $("#board").val();
          let class_id = $("#class").val();
          if (board_id != '' && class_id != '') {
            $.ajax({
                url: '{{ route('get/books/ajax') }}',
                method: 'get',
                data: {
                  board_id:board_id,
                  class_id:class_id,
                },
                success: function(response) {
                  if (response.status == 'success') {
                    $("#book").html(response.books);
                    if (response.books == '<option value="">Select Book</option>') {
                      showNotification('error','Books not found against this board and class');
                    }
                    getStudents(board_id,class_id);
                  }else{
                    var message = response.message;
                    var status = response.status;
                    showNotification(status,message);
                  }
                }
            });
          }else{
            $("#book").html('');
          }
        }

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

        function getStudents(board_id, class_id){
          $.ajax({
              url: '{{ route('admin/test/students') }}',
              method: 'get',
              data: {
                board_id:board_id,
                class_id:class_id,
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
