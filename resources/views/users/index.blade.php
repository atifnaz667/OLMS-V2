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
                    <h5 class="mb-0">Add User</h5>
                    <div class="action-btns">
                        <a href="#" onclick="getdropdowns()" class="btn btn-primary">Assign User</a>

                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md">
                            <label class="form-label" for="username">User Name</label>
                            <input type="text" class="form-control" id="username" placeholder="User Name" />
                        </div>
                        <div class="col-md">
                            <div class="form-password-toggle">
                                <label class="form-label" for="multicol-password">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="multicol-password" class="form-control"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="multicol-password2" />
                                    <span class="input-group-text cursor-pointer" id="multicol-password2"><i
                                            class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md">
                            <label class="form-label" for="role_id">Role</label>
                            <select id="role_id" class="select2 form-select" data-allow-clear="true">
                                <option value="4">Student</option>
                                <option value="2">Parent</option>

                            </select>
                        </div>
                    </div>
                    <button type="button" onclick="addUser()" class="btn btn-primary">Create</button>
                </div>
            </div>
        </div>
        <hr class="my-5">
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-header">Users</h5>
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Sr#</th>
                                    <th>User</th>
                                    <th>User name</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($users as $key => $user)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="sorting_1">
                                            <div class="d-flex justify-content-start align-items-center user-name">
                                                <div class="d-flex flex-column"><a href="#"
                                                        class="text-body text-truncate"><span
                                                            class="fw-semibold">{{ $user->name }}</span></a><small
                                                        class="text-muted">{{ $user->email }}</small></div>
                                            </div>
                                        </td>
                                        <td>{{ $user->username }}</td>
                                        <?php if ($user->role->name == 'Admin') { ?>
                                        <td>
                                            <span class="text-truncate d-flex align-items-center"><span
                                                    class="badge badge-center rounded-pill bg-label-success w-px-30 h-px-30 me-2"><i
                                                        class="ti ti-circle-check ti-sm"></i></span>{{ $user->role->name }}</span>
                                        </td>
                                        <?php } elseif ($user->role->name == 'Parent') { ?>
                                        <td>
                                            <span class="text-truncate d-flex align-items-center"><span
                                                    class="badge badge-center rounded-pill bg-label-info w-px-30 h-px-30 me-2"><i
                                                        class="ti ti-edit ti-sm"></i></span>{{ $user->role->name }}</span>
                                        </td>
                                        <?php  } elseif ($user->role->name == 'Teacher') { ?>
                                        <td>
                                            <span class="text-truncate d-flex align-items-center"><span
                                                    class="badge badge-center rounded-pill bg-label-secondary w-px-30 h-px-30 me-2"><i
                                                        class="ti ti-device-laptop ti-sm"></i></span>{{ $user->role->name }}</span>
                                        </td>
                                        <?php  } else { ?>
                                        <td>
                                            <span class="text-truncate d-flex align-items-center"><span
                                                    class="badge badge-center rounded-pill bg-label-warning w-px-30 h-px-30 me-2"><i
                                                        class="ti ti-user ti-sm"></i></span>{{ $user->role->name }}</span>
                                        </td>
                                        <?php } ?>

                                        <?php if ($user->status == 'active') { ?>
                                        <td><span class="badge bg-label-success"
                                                text-capitalized="">{{ $user->status }}</span></td>
                                        <?php } elseif ($user->status == 'pending') { ?>
                                        <td><span class="badge bg-label-warning"
                                                text-capitalized="">{{ $user->status }}</span></td>
                                        <?php  } elseif ($user->status == 'deactive') { ?>
                                        <td><span class="badge bg-label-secondary"
                                                text-capitalized="">{{ $user->status }}</span></td>
                                        <?php  } ?>
                                        <td>
                                            <!-- Edit Icon -->
                                            <a href="#" onclick="editUser({{ $user->id }})">
                                                <i class="ti ti-edit ti-sm me-2" aria-hidden="true"></i>
                                            </a>

                                            <!-- Delete Icon -->
                                            {{-- <a href="{{ route('user.destroy', $user->id) }}"
                                                onclick="event.preventDefault();
                                                document.getElementById('delete-user-{{ $user->id }}').submit();">
                                                <i class="ti ti-trash ti-sm mx-2" aria-hidden="true"></i>
                                            </a> --}}
                                            <form id="delete-user-{{ $user->id }}"
                                                action="{{ route('user.destroy', $user->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
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
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUpdateUser"
            aria-labelledby="offcanvasUpdateUserLabel">
            <div class="offcanvas-header">
                <h5 id="offcanvasUpdateUserLabel" class="offcanvas-title">Edit User</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body mx-0 flex-grow-0">
                <form class="update-class pt-0" id="addUserForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="update-user-name">User Name</label>
                        <input type="text" class="form-control" required id="update-user-name" placeholder="FBISE"
                            name="update-user-name" aria-label="class" />
                        <input type="hidden" id="userId" name="userId" />
                    </div>
                    <div class="mb-3">
                        <div class="form-password-toggle">
                            <label class="form-label" for="multicol-password-1">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="multicol-password-1" name="multicol-password-1"
                                    class="form-control"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="multicol-password-12" />
                                <span class="input-group-text cursor-pointer" id="multicol-password-12"><i
                                        class="ti ti-eye-off"></i></span>
                            </div>
                            <label class="form-label"> <span class="smaller">If
                                    you
                                    don't want to change password remain the field empty.</span></label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Update</button>
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </form>
            </div>
        </div>
        <div class="modal fade" id="largeModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form class="update-class pt-0" id="AssignUserForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel3">Assign User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">

                                <div class="col mb-3">
                                    <label class="form-label" for="parent_id">Parents</label>
                                    <select id="parent_id" name="parent_id" class="select2 form-select"
                                        data-allow-clear="true">


                                    </select>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col mb-0">
                                    <label class="form-label" for="student_id">Student</label>
                                    <select id="student_id" name="student_id" class="select2 form-select"
                                        data-allow-clear="true">


                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Assign</button>
                        </div>
                    </div>

            </div>
            </form>
        </div>
    @endsection

    @section('page2-script')
        <script>
            const toastAnimationExample = document.querySelector('.toast-ex');
            var offcanvasElement = document.getElementById('offcanvasUpdateUser');
            var offcanvas = new bootstrap.Offcanvas(offcanvasElement);

            function getdropdowns() {
                $.ajax({
                    url: "{{ route('get-dropdown-for-assign') }}",
                    type: 'GET',
                    success: function(response) {
                        var unassignedParents = response.unassignedParents;
                        var unassignedStudents = response.unassignedStudents;

                        // Populate the parent dropdown
                        var parentDropdown = $("#parent_id");
                        parentDropdown.empty(); // Clear previous options

                        unassignedParents.forEach(function(parent) {
                            parentDropdown.append(
                                $("<option></option>").attr("value", parent.id).text(parent.username)
                            );
                        });

                        // Populate the student dropdown
                        var studentDropdown = $("#student_id");
                        studentDropdown.empty(); // Clear previous options

                        unassignedStudents.forEach(function(student) {
                            studentDropdown.append(
                                $("<option></option>").attr("value", student.id).text(student.username)
                            );
                        });

                        // Trigger select2 to update the dropdown appearance
                        parentDropdown.trigger('change');
                        studentDropdown.trigger('change');
                        $('#largeModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
            $('#AssignUserForm').on('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission
                var parent_id = $('#parent_id').val();
                var student_id = $('#student_id').val();
                assignUser(parent_id, student_id);
            });


            function assignUser(parent_id, student_id) {
                $.ajax({
                    url: "{{ route('assign-user') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        parent_id: parent_id,
                        student_id: student_id
                    },
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

            function addUser() {
                const toastAnimationExample = document.querySelector('.toast-ex');
                var password = $('#multicol-password').val();
                var username = $('#username').val();
                var role_id = $('#role_id').val();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('user.store') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        username: username,
                        password: password,
                        role_id: role_id,
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
                        getData();
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
                var userId = $('#userId').val();
                updateUser(userId);
            });
        </script>
        <script>
            var currentPage = 1;
            var lastPage = 1;
            var perPage = 10;

            function editUser(id) {
                $.ajax({
                    url: "{{ route('user.show', '') }}" + "/" + id,
                    type: 'GET',
                    success: function(response) {
                        $('#update-user-name').val(response.User.username);
                        // $('#update-user-status').val(response.User.status);
                        // $('#update-user-role').val(response.User.role.name);
                        $('#userId').val(response.User.id);
                        offcanvas.show();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        // Handle error if necessary
                    }
                });

            }

            function updateUser(id) {
                // Get the form data

                var _token = $('input[name="_token"]').val();
                var username = $('#update-user-name').val();
                var password = $('#multicol-password-1').val();
                var formData = {
                    _token: _token,
                    password: password,
                    username: username
                };
                $.ajax({
                    url: "{{ route('user.update', '') }}" + "/" + id,
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
                        offcanvas.hide();
                        location.reload();

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



            function getData(page = 1) {
                $.ajax({
                    url: '{{ route('topic.index') }}',
                    method: 'GET',
                    data: {
                        chapter_id: chapterId,
                        check: check,
                        page: page,
                        perPage: perPage
                    },
                    success: function(response) {
                        var tableBody = $('.table tbody');
                        tableBody.empty();

                        if (response.status === 'success') {
                            var topics = response.data;
                            currentPage = response.current_page;
                            lastPage = response.last_page;

                            if (topics && topics.length > 0) {
                                $.each(topics, function(index, topic) {
                                    var row = '<tr>' +
                                        '<td>' + (index + 1) + '</td>' +
                                        // '<td>' + topic.board + '</td>' +
                                        // '<td>' + topic.book + '</td>' +
                                        // '<td>' + topic.class + '</td>' +
                                        // '<td>' + topic.topic_no + '</td>' +
                                        '<td>' + topic.name + '</td>' +
                                        "<td>" +
                                        "<a onclick=\"editChapter('" + topic
                                        .id +
                                        "')\" class=\"btn-icon edit-record\"data-id='" + topic
                                        .id +
                                        "'><i class=\"ti ti-edit\"></i></a>" +
                                        "<a class=\"btn-icon delete-topic\" data-id='" + topic.id +
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


            $(document).on('click', '.delete-book', function() {
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
                            url: "{{ route('book.destroy', '') }}" + "/" + user_id,
                            data: {
                                _token: _token,
                            },
                            success: function success(response) {
                                getData();
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

            // Handle pagination click event
            $(document).on('click', '.pagination-link', function(e) {
                e.preventDefault();
                var page = $(this).attr('data-page');
                if (page !== currentPage) {
                    fetchTopicRecords(page);
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
                            '<li class="page-item last"><a class="page-link pagination-link" href="#" data-page="' + lastPage +
                            '"><i class="ti ti-chevrons-right ti-xs"></i></a></li>';
                    }
                    paginationContainer.append(paginationLinks);
                }
            }
        </script>

    @endsection
