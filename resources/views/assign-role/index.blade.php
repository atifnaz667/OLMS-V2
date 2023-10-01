@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')
@section('title', 'Users')

{{-- @section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
@endsection
@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <!-- Flat Picker -->
@endsection --}}



@section('content')

    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Staff Roles Assign</h5>
                    {{--  <div class="action-btns">
                        <a href="#" onclick="getdropdowns()" class="btn btn-primary">Assign User</a>

                    </div>  --}}
                </div>
                <div class="card-body">
                        <div class="container">
                        <div class="row">
                          <div class="col-md">
                                <label class="form-label" for="staff_id">Staff</label>
                                <select id="staff_id"  name="staff_id" class="select2 form-select" required
                                    data-allow-clear="true">
                                    <option value="">Select Staff</option>
                                    @foreach ($staffs as $staff)
                                        <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                    @endforeach

                                </select>
                            </div>

                        </div>
                        </div>

              <div class="container">
                <div class="row mt-3 " id='dynamic-field'>
                    <div class="col-md">
                        <label class="form-label" for="board_id">Board</label>
                        <select id="board_ids" name="board_ids[]" class="select2 form-select" required data-allow-clear="true">
                            <option value="">Select</option>
                            @foreach ($boards as $board)
                              <option value="{{ $board->id }}">{{ $board->name }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="col-md">
                        <label class="form-label" for="class_id">Class</label>
                        <select id="class_ids" name="class_ids[]" class="select2 form-select" required data-allow-clear="true">
                            <option value="">Select</option>
                            @foreach ($classes as $classe)
                              <option value="{{ $classe->id }}">{{ $classe->name }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="col-md">
                        <label class="form-label" for="book_id">Book</label>
                        <select id="book_ids" name="book_ids[]" class="select2 form-select" required data-allow-clear="true">
                            <option value="">Select</option>
                            @foreach ($books as $book)
                              <option value="{{ $book->id }}">{{ $book->name }}</option>
                            @endforeach

                        </select>
                    </div>

                </div>
                        </div>


                          <div class="container" id="dynamic-fields">
                          <!-- This will be populated with the dynamic rows -->
                          </div>


                        <div class="container">
                         <div class="row mt-3">
                          <div class="col-md">
                            <button type="button" class="btn btn-primary" onclick="addRow()">
                            <i class="ti ti-plus me-1"></i>
                              <span class="align-middle">Add</span>
                            </button>
                              <button type="button" class="btn btn-danger" onclick="removeRow()">
                               <i class="ti ti-x ti-xs me-1"></i>
                                    <span class="align-middle">Delete</span>
                              </button>
                          </div>
                         </div>
                         </div>



                        <div
                            class="card-header sticky-element  d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                            <h5 class="card-title mb-sm-0 me-2"></h5>
                            <div class="action-btns">

                                <button type="button" onclick="addUser()" class="btn btn-primary">Create</button>
                            </div>
                        </div>
                    </div>
                    <hr>

            </div>
        </div>
        <hr class="my-5">
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-header">Staffs</h5>
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Sr#</th>
                                    <th>User</th>
                                    <th>User name</th>
                                    <th>Status</th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                              @php
                                $counter = 1;
                              @endphp
                               @foreach ($staffs as $staff)
                                  <tr>
                                      <td>{{ $counter++ }}</td>
                                      <td class="sorting_1">
                                          <div class="d-flex justify-content-start align-items-center user-name">
                                              <div class="d-flex flex-column">
                                                  <a href="#" class="text-body text-truncate">
                                                      <span class="fw-semibold">{{ $staff->name }}</span>
                                                  </a>
                                                  <small class="text-muted">{{ $staff->email ?? '' }}</small>
                                              </div>
                                          </div>
                                      </td>
                                      <td>{{ $staff->username ?? '' }}</td>
                                      <td>
                                          @php
                                          $status = $staff->status;
                                          $badgeClass = '';

                                          switch ($status) {
                                              case 'active':
                                                  $badgeClass = 'bg-label-success';
                                                  break;
                                              case 'pending':
                                                  $badgeClass = 'bg-label-warning';
                                                  break;
                                              case 'deactive':
                                                  $badgeClass = 'bg-label-secondary';
                                                  break;
                                          }
                                          @endphp

                                          <span class="badge {{ $badgeClass }}" text-capitalized="">{{ $status }}</span>
                                      </td>

                                      <td>
                                          <!-- Edit Icon -->
                                          <a href="#" onclick="editStaff({{ $staff->id }})">
                                              <i class="ti ti-edit ti-sm me-2" aria-hidden="true"></i>
                                          </a>

                                          <!-- Delete Icon -->


                                      </td>
                                  </tr>
                              @endforeach

                            </tbody>
                        </table>
                    </div>
                    {{-- <nav aria-label="Page navigation" class="pagination-nav">
                        <ul class="pagination"></ul>
                    </nav> --}}
                </div>
            </div>
        </div>

         {{-- update model for User --}}
   <div class="modal fade" id="largeModal" tabindex="-1" aria-hidden="true" aria-labelledby="largeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <form class="update-class pt-0" id="addUserForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel3">Edit Staff Roles</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container staffs">

                    </div>



                    <div class="container" id="dynamic-fields3">
                        <!-- This will be populated with the dynamic rows -->

                    </div>


                    <div class="container" id="dynamic-fields1">
                        <!-- This will be populated with the dynamic rows -->

                    </div>
                    <div class="container">
                        <div class="row mt-3 us_remove ">
                            <div class="col-md">
                                <label class="form-label" for="board_idss">Board</label>
                                <select id="boardidd" name="board_idss[]" class="select2 form-select board-selectr" data-allow-clear="true">
                                    <option value="">Select</option>
                                    @foreach ($boards as $board)
                                        <option value="{{ $board->id }}">{{ $board->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md">
                                <label class="form-label" for="class_idss">Class</label>
                                <select id="classidd" name="class_idss[]" class="select2 form-select class-selectr" data-allow-clear="true">
                                    <option value="">Select</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md">
                                <label class="form-label" for="book_idss">Book</label>
                                <select id="bookidd" name="book_idss[]" class="select2 form-select book-selectr" data-allow-clear="true">
                                    <option value="">Select</option>
                                    @foreach ($books as $book)
                                        <option value="{{ $book->id }}">{{ $book->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                             <div class="col-md">
                            <button type="button" class="btn btn-primary" onclick="return addRow1()" style="margin-top: 21px;">
                                <i class="ti ti-plus me-1"></i>
                                <span class="align-middle"></span>
                            </button>
                            <button type="button" class="btn btn-danger" onclick="return removeRow1(this)" style="margin-top: 21px;">
                                <i class="ti ti-x ti-xs me-1"></i>
                                <span class="align-middle"></span>
                            </button>
                        </div>

                        </div>


                    </div>




                </div>

                <div class="modal-footer">
                    <button type="submit"  class="btn btn-primary me-sm-3 me-1 data-submit">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>



    @endsection

    @section('page2-script')
      <script>
             const toastAnimationExample = document.querySelector('.toast-ex');
              var offcanvasElement = document.getElementById('largeModal');
              var offcanvas = new bootstrap.Offcanvas(offcanvasElement);

              var boardOptionsU = {!! json_encode($boards) !!};
              var classOptionsU = {!! json_encode($classes) !!};
              var bookOptionsU = {!! json_encode($books) !!};


          function addRow() {
              var newRow = `
                  <div class="row mt-3">
                      <div class="col-md">
                          <label class="form-label" for="board_id">Board</label>
                          <select class="select2 form-select board-select" required data-allow-clear="true">
                              <option value="">Select</option>
                              ${generateOptions(boardOptionsU)}
                          </select>
                      </div>
                      <div class="col-md">
                          <label class="form-label" for="class_id">Class</label>
                          <select class="select2 form-select class-select" required data-allow-clear="true">
                              <option value="">Select</option>
                              ${generateOptions(classOptionsU)}
                          </select>
                      </div>
                      <div class="col-md">
                          <label class="form-label" for="book_id">Book</label>
                          <select class="select2 form-select book-select" required data-allow-clear="true">
                              <option value="">Select</option>
                              ${generateOptions(bookOptionsU)}
                          </select>
                      </div>
                  </div>
              `;

              $("#dynamic-fields").append(newRow);
           }

        function removeRow() {
        var rows = $("#dynamic-fields .row");
        if (rows.length > 0) {
            rows.last().remove();
        }
     }

            function addUser() {


                   const toastAnimationExample = document.querySelector('.toast-ex');
                    var staff_id = $('#staff_id').val();
                    var board_ids = [];
                    var class_ids = [];
                    var book_ids = [];

                    // Get selected board, class, and book IDs from the initial dropdowns
                    var board_id = $('#board_ids').val();
                    var class_id = $('#class_ids').val();
                    var book_id = $('#book_ids').val();

                    if (board_id) {
                        board_ids.push(board_id);
                    }
                    if (class_id) {
                        class_ids.push(class_id);
                    }
                    if (book_id) {
                        book_ids.push(book_id);
                    }

                    // Iterate through the dynamically added rows and collect their values
                    $('#dynamic-fields .row').each(function () {
                        var row = $(this);
                        var board_id = row.find('.board-select').val();
                        var class_id = row.find('.class-select').val();
                        var book_id = row.find('.book-select').val();

                        if (board_id) {
                            board_ids.push(board_id);
                        }
                        if (class_id) {
                            class_ids.push(class_id);
                        }
                        if (book_id) {
                            book_ids.push(book_id);
                        }
                    });



                $.ajax({
                    type: 'POST',
                    url: '{{ route('assignRole.store') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        staff_id: staff_id,
                        board_ids: board_ids,
                        class_ids: class_ids,
                        book_ids: book_ids,
                    },
                    success: function(response) {
                        var status = response.status;
                        var message = response.message;
                        // Update toast message and status
                        $('.toast-ex .fw-semibold').text(status);
                        $('.toast-ex .toast-body').text(message);

                        // Show the toast notification
                        selectedType = "text-success";
                        selectedAnimation = "animate__fade";
                        toastAnimationExample.classList.add(selectedAnimation);
                        toastAnimationExample.querySelector('.ti').classList.add(selectedType);
                        toastAnimation = new bootstrap.Toast(toastAnimationExample);
                        toastAnimation.show();
                        $('#multicol-password').val('');
                        $('#username').val('');

                    },
                    error: function(xhr) {

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




        // edit staff roles modals
        function editStaff(id) {
          $.ajax({
              url: "{{ route('assignRole.show', '') }}" + "/" + id,
              type: 'GET',
              success: function(response) {
                  // Clear the existing dynamic fields
                  $("#dynamic-fields3").empty();

                  // Append the new HTML content from the response to the dynamic-fields2 container
                  $("#dynamic-fields3").append(response.data);
                  $(".staffs").append(response.staffDropdown); // Assuming 'data' contains HTML content

                  // Show the modal
                  $('#largeModal').modal('show');

                  // Set the user ID in the hidden input
                  // $('#userId').val(response.User.id);
              },
              error: function(xhr, status, error) {
                  console.error(error);
                  // Handle error if necessary
              }
          });
      }




               // update staff roles

        function UpdateStaff(id) {

                const toastAnimationExample = document.querySelector('.toast-ex');
                 var staff_idd = $('#staff_idd').val();

                 // Create arrays to store the selected values
                  var board_idss = [];
                  var class_idss = [];
                  var book_idss = [];

                  

                   // Loop through dynamic rows in #dynamic-fields1
                $('#dynamic-fields1 .row').each(function () {
                    var row = $(this);
                    var boardid = row.find('.board-selectr').val();
                    var classid = row.find('.class-selectr').val();
                    var bookid = row.find('.book-selectr').val();

                    // Add values to the respective sets
                    if (boardid) {
                        board_idss.push(boardid);
                    }
                    if (classid) {
                        class_idss.push(classid);
                    }
                    if (bookid) {
                        book_idss.push(bookid);
                    }
                });


                 // Get selected board, class, and book IDs from the initial dropdowns
                    var boardidd = $('#boardidd').val();
                    var classidd = $('#classidd').val();
                    var bookidd = $('#bookidd').val();

                    if (boardidd) {
                            board_idss.push(boardidd);
                        }
                        if (classidd) {
                            class_idss.push(classidd);
                        }
                        if (bookidd) {
                            book_idss.push(bookidd);
                     }

                



                // Loop through dynamic rows in #dynamic-fields3

                 $('#dynamic-fields3 .row').each(function () {
                        var row = $(this);
                        var boardid = row.find('#board_idss').val();
                        var classid = row.find('#class_idss').val();
                        var bookid = row.find('#book_idss').val();

                        if (boardid) {
                            board_idss.push(boardid);
                        }
                        if (classid) {
                            class_idss.push(classid);
                        }
                        if (bookid) {
                            book_idss.push(bookid);
                        }
                    });



                $.ajax({
                    url: "{{ route('assignRole.update', '') }}" + "/" + id,

                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        staff_idd: staff_idd,
                        board_idss: board_idss,
                        class_idss: class_idss,
                        book_idss: book_idss,
                    },
                    success: function(response) {
                        var status = response.status;
                        var message = response.message;
                        // Update toast message and status
                        $('.toast-ex .fw-semibold').text(status);
                        $('.toast-ex .toast-body').text(message);

                        // Show the toast notification
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
                        location.reload();


                    },
                    error: function(xhr) {

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
            $('#addUserForm').on('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission
                var staff_idd = $('#staff_idd').val();
                UpdateStaff(staff_idd);
            });

        // generate options

        function generateOptions(options) {
            var optionHTML = "";
            options.forEach(option => {
                optionHTML += `<option value="${option.id}">${option.name}</option>`;
            });
            return optionHTML;
        }


        // add rows in edit modal of a staff roles

          function addRow1() {

             var newRow1 = `
                  <div class="row mt-3 us_remove">
                      <div class="col-md">
                          <label class="form-label" for="board_id">Board</label>
                          <select class="select2 form-select board-selectr" required data-allow-clear="true">
                              <option value="">Select</option>
                              ${generateOptions(boardOptionsU)}
                          </select>
                      </div>
                      <div class="col-md">
                          <label class="form-label" for="class_id">Class</label>
                          <select class="select2 form-select class-selectr" required data-allow-clear="true">
                              <option value="">Select</option>
                              ${generateOptions(classOptionsU)}
                          </select>
                      </div>
                      <div class="col-md">
                          <label class="form-label" for="book_id">Book</label>
                          <select class="select2 form-select book-selectr" required data-allow-clear="true">
                              <option value="">Select</option>
                              ${generateOptions(bookOptionsU)}
                          </select>
                      </div>

                      <div class="col-md">

            <button type="button" onclick="return removeRow1(this)" class="btn btn-danger delete-row-button" style="
            margin-top: 21px;
        ">
                <i class="ti ti-x ti-xs me-1"></i>
                <span class="align-middle"></span>
            </button>
        </div>

                  </div>
              `;

              $("#dynamic-fields1").append(newRow1);


        }

        // remove rows in staff roles edit modal

        function removeRow1(self)
       {
        $(self).closest('.us_remove').remove()
       }

      // generate option for edit staff roles modal
        function generateOptions1(options) {
            var optionHTML = "";
            options.forEach(option => {
                optionHTML += `<option value="${option.id}">${option.name}</option>`;
            });
            return optionHTML;
        }
    </script>

    @endsection
