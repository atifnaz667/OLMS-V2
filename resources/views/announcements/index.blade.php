@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')
@section('title', 'Announcements')
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
         Announcements
    </h4>
    <!-- Sticky Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                      <div class="col-6">
                        {{-- <h5>Announcements</h5> --}}
                      </div>
                      <div class="col-6 d-flex justify-content-end">
                        <button class="btn btn-primary  mb-3" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">Create</button>
                      </div>
                      <hr>
                      @if (Auth::user()->role_id == 1)
                        <div class="col-12 col-sm-3 mb-3">
                            <label class="form-label" for="class_id">Posted By</label>
                            <select name="posted_by" id="posted_by" class="select2 form-select" data-allow-clear="true">
                              <option value="">Select Value</option>
                              @foreach ($users as $user)
                                <option value="{{ $user->id }}"> {{ $user->name }}</option>
                              @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-3 mb-3">
                            <label class="form-label" for="class_id">Type</label>
                            <select name="type" id="type" class="select2 form-select" data-allow-clear="true">
                              <option value="">All</option>
                              <option value="Admin">Admin</option>
                              <option value="Teacher">Teacher</option>
                            </select>
                        </div>
                      @endif

                        <div class="col-12 col-sm-3 mb-3">
                            <label class="form-label" for="class_id">Status</label>
                            <select name="status" id="status" class="select2 form-select">
                              <option value="">All</option>
                              <option value="Published">Published</option>
                              <option value="Unpublished">Unpublished</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-3 mb-3">
                            <label class="form-label" for="class_id">Date</label>
                            <input type="date" name="date" id="date" class="form-control">
                        </div>
                        <div class="row ">
                          <div class="col-12 d-flex justify-content-end">
                            <button type="button" onclick="fetchAnnouncementRecords()" class="btn btn-primary"
                                id="filterButton">Filter</button>
                          </div>
                        </div>
                    </div>
                </div>
                <hr>
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
                      <input type="text" id="search-input" class="form-control" placeholder="Search By Title">
                  </div>
              </div>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Sr#</th>
                                <th>Posted By</th>
                                <th>Board</th>
                                <th>Title</th>
                                <th>Status</th>
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


    <div class="modal fade" id="addAnnouncementModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
          <form class=" pt-0" id="addAnnouncementForm">
              @csrf
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel3" >Add Announcement</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">

                    <div class="row">
                      <div class="col-12 col-sm-6 mb-3"
                      @if (Auth::user()->role_id == 3) style="display: none" @endif>
                        <label class="form-label" for="class_id">Status</label>
                        <select name="status" id="add-status" class="select2 form-select"  required>
                          <option value="Published">Published</option>
                          <option value="Unpublished">Unpublished</option>
                        </select>
                      </div>
                      <div class="col-12 col-sm-6 mb-3">
                        <label class="form-label" for="class_id">Board</label>
                        <select id="add-board" name="board" class="select2 form-select" >
                          <option value="0">All</option>
                          @foreach ($boards as $board)
                            <option value="{{ $board->id }}">{{ $board->name }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-12 col-sm-12 mb-3">
                        <label class="form-label" for="class_id">Classes</label>
                        <select id="add-classes" name="classes[]" class="select2 form-select" multiple  required>
                          <option value="">Select Classes</option>
                          @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                          @endforeach
                        </select>
                      </div>

                    </div>
                      <div class="row">
                          <div class="col mb-3">
                              <input type="hidden" id="announcement_id" name="announcement_id" />
                              <label class="form-label" for="update-title">Title</label>
                              <textarea required id="add-title" name="title" rows="3" class="form-control" placeholder="Enter Title"></textarea>
                          </div>
                      </div>
                      <div class="row g-2">
                          <div class="col mb-0">
                              <label class="form-label" for="">Description</label>
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
          <form class="update-class pt-0" id="viewAnnouncementForm">
              @csrf
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel3">View Announcement</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">

                    <div class="row">
                      <div class="col-12 col-sm-6 mb-3" @if (Auth::user()->role_id == 3) style="display: none" @endif>
                        <label class="form-label" for="class_id">Status</label>
                        <select name="update_status" id="update_status" class="select2 form-select"  required>
                          <option value="Published">Published</option>
                          <option value="Unpublished">Unpublished</option>
                        </select>
                    </div>
                      <div class="col-12 col-sm-6 mb-3">
                        <label class="form-label" for="class_id">Board</label>
                        <select id="update_board" name="update_board" class="select2 form-select">
                        </select>
                      </div>
                      <div class="col-12 col-sm-12 mb-3">
                        <label class="form-label" for="class_id">Classes</label>
                        <select id="update_classes" name="update_classes[]" class="select2 form-select" multiple  required>
                        </select>
                      </div>

                    </div>
                      <div class="row">
                          <div class="col mb-3">
                              <input type="hidden" id="announcement_id" name="announcement_id" />
                              <label class="form-label" for="update-title">Title</label>
                              <textarea required id="update_title" name="update_title" rows="3" class="form-control" placeholder="Enter Title"></textarea>
                          </div>
                      </div>
                      <div class="row g-2">
                          <div class="col mb-0">
                              <label class="form-label" for="">Description</label>
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

        function fetchAnnouncementRecords(page = 1) {
            var date = $('#date').val();
            var status = $('#status').val();
            var type = $('#type').val();
            var posted_by = $('#posted_by').val();
            var title = $('#search-input').val();
            var perPage = $('#perPageSelect').val();

            $.ajax({
                url: '{{ route('announcement.ajax') }}',
                method: 'GET',
                data: {
                    date: date,
                    page: page,
                    perPage: perPage,
                    status: status,
                    type: type,
                    posted_by: posted_by,
                    title: title,
                },
                success: function(response) {
                    var tableBody = $('.table tbody');
                    tableBody.empty();

                    if (response.status === 'success') {
                        var announcements = response.data;
                        currentPage = response.current_page;
                        lastPage = response.last_page;

                        if (announcements && announcements.length > 0) {
                            $.each(announcements, function(index, announcement) {
                                var row = '<tr>' +
                                    '<td>' + (index + 1) + '</td>' +
                                    '<td>' + announcement.user + '</td>' +
                                    '<td>' + announcement.board + '</td>' +
                                    '<td>' + announcement.title + '</td>' +
                                    '<td>' + announcement.status + '</td>' +
                                    '<td>' + announcement.date + '</td>' +
                                    "<td>" +
                                    "<a onclick=\"editAnnouncement('" + announcement
                                    .id +
                                    "')\" class=\"btn-icon edit-record\"data-id='" + announcement
                                    .id +
                                    "'><i class=\"ti ti-edit\"></i></a>" +
                                    "<a class=\"btn-icon delete-announcement\" data-id='" +
                                    announcement.id +
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
            var announcement_id = $(this).data('id'),
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
                        url: "{{ route('announcement.destroy', '') }}" + "/" + announcement_id,
                        data: {
                          _token:'{{ csrf_token() }}'
                        },
                        success: function success(response) {
                            fetchAnnouncementRecords(currentPage)
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
            fetchAnnouncementRecords();
        });

        // Handle pagination click event
        $(document).on('click', '.pagination-link', function(e) {
            e.preventDefault();
            var page = $(this).attr('data-page');
            if (page !== currentPage) {
                fetchAnnouncementRecords(page);
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
        fetchAnnouncementRecords();

        $("#search-input").keypress(function(e) {
        if (e.which == 13) {
          fetchAnnouncementRecords();
        }
        });

        function editAnnouncement(id) {
            $.ajax({
                url: "{{ route('announcement.show', '') }}" + "/" + id,
                type: 'GET',
                success: function(response) {
                    // Update the form fields with the fetched data
                    $('#update_description').summernote('code', response.announcement.description);
                    $('#update_title').val(response.announcement.title);
                    $('#announcement_id').val(response.announcement.id);
                    $('#update_status').html(response.status);
                    $('#update_board').html(response.boards);
                    $('#update_classes').html(response.classes);
                    $('#largeModal').modal('show');


                },
                error: function(xhr, status, error) {
                    console.error(error);
                    // Handle error if necessary
                }
            });
        }
        $('#viewAnnouncementForm').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            var announcement_id = $('#announcement_id').val();
            updateAnnouncement(announcement_id);
        });


        $('#addAnnouncementForm').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            addAnnouncement();
        });

        function addAnnouncement() {
            var _token = '{{ csrf_token() }}';
            var description = $('#add-description').val();
            var title = $('#add-title').val();
            var classes = $('#add-classes').val();
            var board = $('#add-board').val();
            var status = $('#add-status').val();
            var formData = {
              _token: _token,
              description: description,
              title: title,
              classes: classes,
              status: status,
              board: board,
            };
            $.ajax({
                url: "{{ route('announcement.store') }}",
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
                    $('#add-classes').prop('selectedIndex',0);
                    $('#add-title').val('');
                    $('#add-description').summernote('code', '');
                    fetchAnnouncementRecords()
                    $('#addAnnouncementModal').modal('hide');

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
        function updateAnnouncement(id) {
            var _token = '{{ csrf_token() }}';
            var update_description = $('#update_description').val();
            var update_title = $('#update_title').val();
            var update_classes = $('#update_classes').val();
            var update_board = $('#update_board').val();
            var update_status = $('#update_status').val();
            var formData = {
              _token: _token,
                update_description: update_description,
                update_title: update_title,
                update_classes: update_classes,
                update_status: update_status,
                update_board: update_board,
            };
            $.ajax({
                url: "{{ route('announcement.update', '') }}" + "/" + id,
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
                    fetchAnnouncementRecords(currentPage)
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
