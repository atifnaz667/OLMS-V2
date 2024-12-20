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
                            <label class="form-label" for="full_name">Full Name</label>
                            <input type="text" class="form-control" id="full_name" placeholder="Full Name" />
                        </div>
                        <div class="col-md">
                            <label class="form-label" for="username">User Name</label>
                            <input type="text" class="form-control" id="username" placeholder="User Name" />
                        </div>
                        {{-- <div class="col-md">
                            <label class="form-label" for="card_no">Card no.</label>
                            <input type="text" class="form-control" id="card_no" placeholder="Card Number" />
                        </div> --}}
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
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label" for="role_id">Role</label>
                            <select id="role_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                                @foreach ($roles as $roles)
                                    <option value="{{ $roles->id }}">{{ $roles->name }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter Email" />
                        </div>
                    </div>
                    <button type="button" onclick="addUser()" class="btn btn-primary">Create</button>
                    <hr class="my-5">
                    <h5 class="mt-3">Add Card</h5>
                    <div class="row mb-3">
                        <div class="col-md">
                            <label class="form-label" for="serial_no">Serial No.</label>
                            <input type="text" class="form-control" required id="serial_no"
                                placeholder="Serial Number" />
                        </div>
                        <div class="col-md">
                            <label class="form-label" for="card_no">Card No.</label>
                            <input type="text" class="form-control" id="card_no" placeholder="Card Number" />
                        </div>

                        <div class="col-md">
                            <div class="form-password-toggle">
                                <label class="form-label" for="expiryDate">Expiry Date</label>
                                <!-- <div class="input-group input-group-merge">
                                                                                                                                                                                                                                                            <input type="date" id="expiryDate" class="form-control" />
                                                                                                                                                                                                                                                        </div> -->
                                <select id="expiryDate" name="expiryDate" class="select2 form-select"
                                    data-allow-clear="true">
                                    <option value="">Select Option</option>
                                    <option value="One Year">One Year</option>
                                    <option value="Six Months">Six Months</option>

                                </select>
                            </div>
                        </div>

                        <!-- <div class="col-md">
                                                                                                                                                                                                                                                    <div class="form-password-toggle">
                                                                                                                                                                                                                                                        <label class="form-label" for="validDate">Valid Till</label>
                                                                                                                                                                                                                                                        <div class="input-group input-group-merge">
                                                                                                                                                                                                                                                            <input type="date" id="validDate" class="form-control" />
                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                </div> -->
                    </div>
                    <button type="button" onclick="addCard()" class="btn btn-primary">Create</button>
                </div>
            </div>
        </div>
        <hr class="my-5">
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">


                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#form-tabs-board-listing" role="tab"
                                    aria-selected="true">Users</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link " data-bs-toggle="tab" data-bs-target="#form-tabs-class-listing"
                                    role="tab" aria-selected="false">Cards</button>
                            </li>
                        </ul>
                    </div>



                    <div class="tab-content">
                        <!-- Personal Info -->
                        <div class="tab-pane fade active show" id="form-tabs-board-listing" role="tabpanel">
                            {{-- <div class="card"> --}}
                            <h5 class="card-header">Users</h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Sr#</th>
                                            <th>User</th>
                                            <th>User name</th>
                                            <th>Role</th>
                                            <th>Card Sr#</th>
                                            <th>Account Status</th>
                                            <th>Status</th>
                                            <th>Last Seen</th>
                                            <th>Last Login At</th>
                                            <th>Last Activity At</th>
                                            <th>View</th>
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
                                                {{-- <td>{{ $user->cardno }}</td> --}}
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
                                                <td>{{ $user->card_id }}</td>
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
                                                    @if (Cache::has('user-is-online-' . $user->id))
                                                        <span class="text-success">Online</span>
                                                    @else
                                                        <span class="text-secondary">Offline</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($user->last_seen != null)
                                                        {{ \Carbon\Carbon::parse($user->last_seen)->diffForHumans() }}
                                                    @else
                                                    @endif
                                                </td>
                                                <td>{{ $user->last_login_at }}</td>
                                                <td>{{ $user->last_activity_at }}</td>
                                                <td> <button type="button" class="btn"
                                                        onclick="details({{ $user->id }})">
                                                        <i class="ti ti-eye ti-sm" aria-hidden="true"></i>
                                                    </button></td>
                                                <td>
                                                    <!-- Edit Icon -->
                                                    <a href="#" onclick="editUser({{ $user->id }})">
                                                        <i class="ti ti-edit ti-sm" aria-hidden="true"></i>
                                                    </a>
                                                    <!-- Delete Icon -->
                                                    <a href="#" class="delete-user-btn"
                                                        data-user-id="{{ $user->id }}">
                                                        <i class="ti ti-trash ti-sm" aria-hidden="true"></i>
                                                    </a>
                                                    <form class="delete-user-form" data-user-id="{{ $user->id }}"
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
                        </div>
                        <!-- Account Details -->
                        <div class="tab-pane fade" id="form-tabs-class-listing" role="tabpanel">
                            {{-- <div class="card"> --}}
                            <h5 class="card-header">Cards</h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Sr#</th>
                                            <th>Serial No.</th>
                                            <th>Card No.</th>
                                            <th>Expiry Date</th>
                                            <!-- <th>Valid till</th> -->
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @foreach ($cards as $key => $user)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $user->serial_no }}</td>
                                                <td>{{ $user->card_no }}</td>
                                                <td>{{ $user->expiry_date }}</td>
                                                <!-- <td>{{ $user->valid_date }}</td> -->
                                                <?php if ($user->status == 'used') { ?>
                                                <td><span class="badge bg-label-success" text-capitalized="">Used</span>
                                                </td>
                                                <?php } elseif ($user->status == 'expired') { ?>
                                                <td><span class="badge bg-label-warning"
                                                        text-capitalized="">Expired</span></td>
                                                <?php  }else { ?>
                                                <td><span class="badge bg-label-secondary"
                                                        text-capitalized="">{{ $user->status }}</span></td>
                                                <?php  } ?>
                                                <td>
                                                    <!-- Edit Icon -->
                                                    <a href="#" onclick="editCard({{ $user->id }})">
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
                            {{-- </div> --}}
                        </div>
                    </div>



                    <div class="card-header">
                        <h5 class="card-header">Users</h5>
                    </div>

                    {{-- <nav aria-label="Page navigation" class="pagination-nav">
                        <ul class="pagination"></ul>
                    </nav> --}}
                </div>
            </div>
        </div>

        {{-- update model for User --}}
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUpdateCard"
            aria-labelledby="offcanvasUpdateCardLabel">
            <div class="offcanvas-header">
                <h5 id="offcanvasUpdateCardLabel" class="offcanvas-title">Edit User</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body mx-0 flex-grow-0">
                <form class="update-card pt-0" id="addCardForm">
                    @csrf
                    <div class="col-md">
                        <label class="form-label" for="update_serial_no">Serial No.</label>
                        <input type="text" class="form-control" required name="update_serial_no"
                            id="update_serial_no" placeholder="Serial Number" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="update-card">Card No</label>
                        <input type="text" class="form-control" required id="update-card" name="update-card"
                            aria-label="class" />
                        <input type="hidden" id="cardId" name="cardId" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="update-expiry-date">Expiry Date</label>
                        <!-- <input type="date" class="form-control" required
                                                                                                                                                                                                                                                    id="update-expiry-date"name="update-expiry-date" aria-label="class" /> -->
                        <select id="update-expiry-date" name="update-expiry-date" class="select2 form-select"
                            data-allow-clear="true">
                            <option value="">Select Option</option>
                            <option value="One Year">One Year</option>
                            <option value="Six Months">Six Months</option>

                        </select>
                    </div>
                    <!-- <div class="mb-3">
                                                                                                                                                                                                                                                <label class="form-label" for="update-valid-date">Valid Till</label>
                                                                                                                                                                                                                                                <input type="date" class="form-control" required
                                                                                                                                                                                                                                                    id="update-valid-date"name="update-valid-date" aria-label="class" />
                                                                                                                                                                                                                                            </div> -->

                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Update</button>
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </form>
            </div>
        </div>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUpdateUser"
            aria-labelledby="offcanvasUpdateUserLabel">
            <div class="offcanvas-header">
                <h5 id="offcanvasUpdateUserLabel" class="offcanvas-title">Edit User</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
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
                        <label class="form-label" for="board_id">Board</label>
                        <select id="board_id" name="board_id" class="select2 form-select" data-allow-clear="true">
                            <option value="">Select</option>
                            @foreach ($boards as $board)
                                <option value="{{ $board->id }}">{{ $board->name }}</option>
                            @endforeach


                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="class_id">Class</label>
                        <select id="class_id" name="class_id" class="select2 form-select" data-allow-clear="true">
                            <option value="">Select</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        {{-- <div class="form-password-toggle"> --}}
                        <label class="form-label" for="update-password">Password</label>
                        <div class="input-group input-group">
                            <input type="password" id="update-password" name="update-password" class="form-control"
                                placeholder="" />
                            <span class="input-group-text cursor-pointer" id="update-password2"><i
                                    class="ti ti-eye-off"></i></span>
                        </div>
                        <label class="form-label"> <span class="smaller">If you don't want to change Board Class and
                                Password remain the field empty.</span></label>
                        {{-- </div> --}}
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

                            <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Assign</button>
                        </div>
                    </div>

            </div>
            </form>
        </div>
        <div class="modal fade" id="largeModal2" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form class="update-class pt-0" id="AssignUserForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel3">Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive text-nowrap">
                                <table class="table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0" id="details">
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">

                                {{-- <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Assign</button> --}}
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    @endsection

    @section('page2-script')
        <script>
            const toastAnimationExample = document.querySelector('.toast-ex');
            var offcanvasElement = document.getElementById('offcanvasUpdateUser');
            var offcanvasElement2 = document.getElementById('offcanvasUpdateCard');
            var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
            var offcanvas2 = new bootstrap.Offcanvas(offcanvasElement2);

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

            function addCard() {
                const toastAnimationExample = document.querySelector('.toast-ex');
                var card_no = $('#card_no').val();
                var serial_no = $('#serial_no').val();
                var expiryDate = $('#expiryDate').val();
                // var validDate = $('#validDate').val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('user.store') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        card_no: card_no,
                        serial_no: serial_no,
                        expiryDate: expiryDate,
                        // validDate: validDate,
                        check: "card",
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
                        $('#card_no').val('');
                        $('#serial_no').val('');
                        // getData();
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

            function addUser() {
                const toastAnimationExample = document.querySelector('.toast-ex');
                var password = $('#multicol-password').val();
                var username = $('#username').val();
                var role_id = $('#role_id').val();
                var full_name = $('#full_name').val();
                var email = $('#email').val();
                // var card_no = $('#card_no').val();
                console.log(full_name)
                $.ajax({
                    type: 'POST',
                    url: '{{ route('user.store') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        username: username,
                        password: password,
                        role_id: role_id,
                        full_name: full_name,
                        email: email,
                        // card_no: card_no,
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
                        // getData();
                        // location.reload();

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
            $('#addCardForm').on('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission
                var cardId = $('#cardId').val();
                updateCard(cardId);
            });
        </script>
        <script>
            var currentPage = 1;
            var lastPage = 1;
            var perPage = 10;

            function details(id) {
                $.ajax({
                    url: "{{ url('detail-user', '') }}" + "/" + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $('#details').empty();
                        var heading = '';
                        if (response.role == 2) {
                            heading = 'Child: ';
                            if (response.details) {
                                $('#details').append('<tr><td>' + heading + response.details + '</td></tr>');
                            }
                        } else if (response.role == 3) {
                            heading = '';
                            if (response.details) {
                                response.details.forEach(function(detail) {
                                    $('#details').append('<tr><td>' + heading + detail.name + '</td></tr>');
                                });
                            }
                        } else if (response.role == 4) {
                            heading = 'Parent: ';
                            if (response.details) {
                                $('#details').append('<tr><td>' + heading + response.details + '</td></tr>');
                            }
                            if (response.class) {

                                $('#details').append('<tr><td>Class: ' + response.class + '</td></tr>');
                            }
                        }

                        // $('#details').append('<tr><th>' + heading + '</th></tr>');

                        // Display class if available
                        // if (response.class) {
                        //     $('#details').append('<tr><th>Class</th></tr>');
                        //     $('#details').append('<tr><td>' + response.class + '</td></tr>');
                        // }

                        $('#largeModal2').modal('show');
                    },
                    error: function(error) {
                        console.error('Error fetchings details:', error);
                    }
                });
            }

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

            function editCard(id) {
                $.ajax({
                    url: "{{ route('user.show', '') }}" + "/" + id,
                    type: 'GET',
                    data: {
                        check: "card"
                    },
                    success: function(response) {
                        $('#update-card').val(response.User.card_no);
                        $('#update_serial_no').val(response.User.serial_no);
                        // $('#update-expiry-date').val(response.User.expiry_date);
                        var updateValidDate = response.User.expiry_date;
                        console.log(updateValidDate)
                        $('#update-expiry-date').val(updateValidDate).trigger('change');
                        // $('#update-valid-date').val(response.User.valid_date);
                        $('#cardId').val(response.User.id);
                        offcanvas2.show();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        // Handle error if necessary
                    }
                });

            }

            function updateCard(id) {
                // Get the form data

                var _token = $('input[name="_token"]').val();
                var card_no = $('#update-card').val();
                var update_serial_no = $('#update_serial_no').val();
                var expiry_date = $('#update-expiry-date').val();
                // var valid_date = $('#update-valid-date').val();

                var formData = {
                    _token: _token,
                    card_no: card_no,
                    update_serial_no: update_serial_no,
                    expiry_date: expiry_date,
                    // valid_date: valid_date
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

            function updateUser(id) {
                // Get the form data

                var _token = $('input[name="_token"]').val();
                var username = $('#update-user-name').val();
                var password = $('#update-password').val();
                var board_id = $('#board_id').val();
                var class_id = $('#class_id').val();
                var formData = {
                    _token: _token,
                    password: password,
                    board_id: board_id,
                    class_id: class_id,
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
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.delete-user-btn').forEach(function(button) {
                    button.addEventListener('click', function(event) {
                        event.preventDefault();
                        var userId = this.getAttribute('data-user-id');
                        var confirmDelete = confirm('Are you sure you want to delete this user?');
                        if (confirmDelete) {
                            fetch('{{ route('user.destroy', ':userId') }}'.replace(':userId',
                                    userId), {
                                    method: 'DELETE',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    },
                                })
                                .then(response => {
                                    if (response.ok) {
                                        var status = response.status;
                                        var message = response.message;
                                        $('.toast-ex .fw-semibold').text(status);
                                        $('.toast-ex .toast-body').text(message);
                                        selectedType = "text-success";
                                        selectedAnimation = "animate__fade";
                                        toastAnimationExample.classList.add(selectedAnimation);
                                        toastAnimationExample.querySelector('.ti').classList.add(
                                            selectedType);
                                        toastAnimation = new bootstrap.Toast(toastAnimationExample);
                                        toastAnimation.show();
                                        location.reload();
                                    } else {
                                        console.error('Error deleting user');
                                    }
                                })
                                .catch(error => {
                                    console.error('Network error:', error);
                                });
                        }
                    });
                });
            });
        </script>

    @endsection
