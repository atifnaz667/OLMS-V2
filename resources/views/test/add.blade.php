@extends('layouts/layoutMaster')
@section('title', 'Create Test')
@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Test/</span>
        Create
    </h4>

    <div class="row">
        <div class="col-12">
            <div class="card">
              <h5 class="card-header">Create Test</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Sr#</th>
                                <th>Student Name</th>
                                <th>Class</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                          @php($i = 0)
                          @foreach ($students as $student)
                          @php($i++)
                            <tr>
                              <td>{{ $i }}</td>
                              <td>{{ $student->child->name }}</td>
                              <td>{{ $student->child->class->name }}</td>
                              <td>
                                <button type="button" onclick="getBooks({{ $student->child_id }})" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#exLargeModal"> Create Test</button>
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                    </table>
                </div>
                <nav aria-label="Page navigation" class="pagination-nav">
                    <ul class="pagination"></ul>
                </nav>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exLargeModal" tabindex="-1" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel4">Create Test</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="createTestForm" method="post">
              @csrf
              <div class="row">
                <div class="col-sm-3 mb-3">
                  <label for="nameExLarge" class="form-label">Select Book</label>
                  <select name="book" id="book" class="form-select" onchange="getChapters(this.value)" required>
                  </select>
                </div>
              </div>
              <div class="row px-3 mt-3 mb-5" id="chaptersRow">
                <h6>Please select book</h6>
              </div>
              <div class="row px-4">
                <div class="col-sm-3 mb-3">
                  <label for="nameExLarge" class="form-label">Test Time</label>
                  <input type="datetime-local" class="form-control" name="testDate"  required>
                </div>
                <div class="col-sm-3 mb-3">
                  <label for="nameExLarge" class="form-label">Expiry Time</label>
                  <input type="datetime-local" class="form-control" name="expiryDate"  required>
                </div>
                <div class="col-sm-3 mb-3">
                  <label for="nameExLarge" class="form-label">Total Questions</label>
                  <input type="input" class="form-control" name="totalQuestions" placeholder="Enter Total Questions" required value="10">
                </div>
                <input type="hidden" name="testUserId[]" id="testUserId">
                <div class="col-sm-3 mb-3">
                  <label for="nameExLarge" class="form-label">Time For Each Question</label>
                  <select name="questionTime" id="questionTime" class="form-select" required>
                    {!! $timeOptions !!}
                  </select>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">

            <button type="button" id="submitTest" class="btn btn-primary waves-effect waves-light">Create Test</button>
          </div>
        </div>
      </div>
    </div>
@endsection

@section('script')

    <script>
      function getBooks(userId){
          $("#testUserId").val(userId);
          $("#chaptersRow").html('<h6>Please select book</h6>');
          $.ajax({
            url: '{{ route('test/books') }}',
            method: 'get',
            data: {
              userId:userId,
            },
            success: function(response) {
              $("#book").html(response);
            }
        });
      }


      function getChapters(bookId){
        let userId = $("#testUserId").val();
        $.ajax({
              url: '{{ route('test/chapters') }}',
              method: 'get',
              data: {
                userId:userId,
                bookId:bookId,
              },
              success: function(response) {
                $("#chaptersRow").html(response);
              }
          });
      }

        // Select All checkbox
        function selectCheckboxes(isChecked){
          var isChecked = $('#select-all').prop('checked');
          $('.checkboxes').prop('checked', isChecked);
        }

        function selectCheckbox() {
          if ($('.checkboxes:checked').length === $('.checkboxes').length) {
            $('#select-all').prop('checked', true);
          } else {
            $('#select-all').prop('checked', false);
          }
        }

        $(document).ready(function() {

            $('#submitTest').click(function() {
                const toastAnimationExample = document.querySelector('.toast-ex');
                var form = $('#createTestForm');
                if (form[0].checkValidity() === false) {
                    form.addClass('was-validated');
                    return;
                }

                var formData = form.serialize();

                $.ajax({
                    url: '{{ route('test/store') }}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                      var status = response.status;
                      if (status == 'success') {
                        $("#exLargeModal").modal('hide');
                      }
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
