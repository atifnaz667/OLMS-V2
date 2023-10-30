@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')
@section('title', 'Notes')
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
         Notes
    </h4>
    <!-- Sticky Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                      <div class="col-6">

                      </div>
                      <div class="col-6 d-flex justify-content-end">
                        <button class="btn btn-primary  mb-3" data-bs-toggle="modal" data-bs-target="#addNotestModal">Create</button>
                      </div>
                      <hr>
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
                  <div class="col-4">
                    <label class="form-label" for="class_id">Date</label>
                    <input type="date" name="date" id="date" class="form-control">
                  </div>
                  <div class="col-4  mt-4">
                   <button type="button" onclick="fetchNoteRecords()" class="btn btn-primary"
                   id="filterButton">Filter</button>
                  </div>
              </div>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Sr#</th>
                                <th>Name</th>
                                <th>Note</th>
                                <th>Created At</th>
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


    <div class="modal fade" id="addNotestModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
          <form class=" pt-0" id="addNoteForm">
              @csrf
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel3" >Add Notes</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">



                      <div class="row g-2">
                          <div class="col mb-0">
                              <label class="form-label" for="">Note</label>
                              <textarea required id="add-description" name="description" rows="10" class="form-control summernote"></textarea>
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
    <div class="modal fade" id="largeModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
          <form class="update-class pt-0" id="viewNoteForm">
              @csrf
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel3">View Note</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">

                      <div class="row">
                          <div class="col mb-3">
                              <input type="hidden" id="note_id" name="note_id" />

                          </div>
                      </div>
                      <div class="row g-2">
                          <div class="col mb-0">
                              <label class="form-label" for="">Note</label>
                              <textarea required id="update_description" name="update_description" rows="10" class="form-control summernote"></textarea>
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

        $('.summernote').summernote({
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
        var currentPage = 1;
        var lastPage = 1;
        const toastAnimationExample = document.querySelector('.toast-ex');

        function fetchNoteRecords(page = 1) {
            var date = $('#date').val();
            var perPage = $('#perPageSelect').val();

            $.ajax({
                url: '{{ route('notes.ajax') }}',
                method: 'GET',
                data: {
                    date: date,
                    page: page,
                    perPage: perPage,

                },
                success: function(response) {
                    var tableBody = $('.table tbody');
                    tableBody.empty();

                    if (response.status === 'success') {
                        var notes = response.data;
                        currentPage = response.current_page;
                        lastPage = response.last_page;

                        if (notes && notes.length > 0) {
                            $.each(notes, function(index, note) {
                                var row = '<tr>' +
                                    '<td>' + (index + 1) + '</td>' +
                                    '<td>' + note.user + '</td>' +
                                    '<td>' + note.note + '</td>' +
                                    '<td>' + note.date + '</td>' +
                                    "<td>" +
                                    "<a onclick=\"editNote('" + note
                                    .id +
                                    "')\" class=\"btn-icon edit-record\"data-id='" + note
                                    .id +
                                    "'><i class=\"ti ti-edit\"></i></a>" +
                                    "<a class=\"btn-icon delete-announcement\" data-id='" +
                                    note.id +
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

        $(document).on('click', '.delete-announcement', function() {
            var _token = $('input[name="_token"]').val();
            var note_id = $(this).data('id'),
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
                        type: 'delete',
                        url: "{{ route('notes.destroy', '') }}" + "/" + note_id,
                        data: {
                          _token:'{{ csrf_token() }}'
                        },
                        success: function success(response) {
                            fetchNoteRecords(currentPage)
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

        $('#filterButton, #perPageSelect').on('change', function(e) {
            e.preventDefault();
            currentPage = 1; // Reset to first page when filter is applied
            perPage = $('#perPageSelect').val();
            fetchNoteRecords();
        });

        // Handle pagination click event
        $(document).on('click', '.pagination-link', function(e) {
            e.preventDefault();
            var page = $(this).attr('data-page');
            if (page !== currentPage) {
                fetchNoteRecords(page);
            }
        });

        // Update pagination UI
        function updatePaginationUI() {
            var paginationContainer = $('.pagination');
            paginationContainer.empty();

            if (lastPage > 1) {
                var paginationLinks = '';
                if (currentPage > 1) {
                    paginationLinks +=
                        '<li class="page-item first"><a class="page-link pagination-link" href="#" data-page="1"><i class="ti ti-chevrons-left ti-xs"></i></a></li>';
                    paginationLinks +=
                        '<li class="page-item prev"><a class="page-link pagination-link" href="#" data-page="' + (
                            currentPage - 1) + '"><i class="ti ti-chevron-left ti-xs"></i></a></li>';
                }
                for (var i = 1; i <= lastPage; i++) {
                    var activeClass = (i === currentPage) ? 'active' : '';
                    paginationLinks += '<li class="page-item ' + activeClass +
                        '"><a class="page-link pagination-link" href="#" data-page="' + i + '">' + i + '</a></li>';
                }
                if (currentPage < lastPage) {
                    paginationLinks +=
                        '<li class="page-item next"><a class="page-link pagination-link" href="#" data-page="' + (
                            currentPage + 1) + '"><i class="ti ti-chevron-right ti-xs"></i></a></li>';
                    paginationLinks +=
                        '<li class="page-item last"><a class="page-link pagination-link" href="#" data-page="' +
                        lastPage +
                        '"><i class="ti ti-chevrons-right ti-xs"></i></a></li>';
                }
                paginationContainer.append(paginationLinks);
            }
        }

        // Initial fetch and pagination UI update
        fetchNoteRecords();

        $("#search-input").keypress(function(e) {
        if (e.which == 13) {
          fetchNoteRecords();
        }
        });

        function editNote(id) {
            $.ajax({
                url: "{{ route('notes.show', '') }}" + "/" + id,
                type: 'GET',
                success: function(response) {
                    // Update the form fields with the fetched data
                    $('#update_description').summernote('code', response.note.note);
                    $('#note_id').val(response.note.id);
                    $('#largeModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    // Handle error if necessary
                }
            });
        }
        $('#viewNoteForm').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            var note_id = $('#note_id').val();
            updateNote(note_id);
        });


        $('#addNoteForm').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            addNote();
        });

        function addNote() {
            var _token = '{{ csrf_token() }}';
            var note = $('#add-description').val();

            var formData = {
              _token: _token,
              note: note,
            };
            $.ajax({
                url: "{{ route('notes.store') }}",
                type: 'POST',
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
                    $('#add-description').summernote('code', '');
                    fetchNoteRecords()
                    $('#addNotestModal').modal('hide');

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
        function updateNote(id) {
            var _token = '{{ csrf_token() }}';
            var update_description = $('#update_description').val();
            var formData = {
              _token: _token,
                update_description: update_description,
            };
            $.ajax({
                url: "{{ route('notes.update', '') }}" + "/" + id,
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
                    fetchNoteRecords(currentPage)
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
    </script>
@endsection
