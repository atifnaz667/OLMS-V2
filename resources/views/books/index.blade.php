@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')


@section('title', 'BOOKS')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <!-- Flat Picker -->
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
@endsection


@section('content')
    {{-- <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Home/</span>
        Books
    </h4> --}}
    <div class="bs-toast toast toast-ex animate__animated my-2" role="alert" aria-live="assertive" aria-atomic="true"
        data-bs-delay="2000">
        <div class="toast-header">
            <i class="ti ti-bell ti-xs me-2"></i>
            <div class="me-auto fw-semibold">Bootstrap</div>
            <small class="text-muted">11 mins ago</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Hello, world! This is a toast messagesss.
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h6 class="mt-4"> Add Board Book Class </h6>
            <div class="card mb-3">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#form-tabs-personal"
                                role="tab" aria-selected="true">Board</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link " data-bs-toggle="tab" data-bs-target="#form-tabs-account"
                                role="tab" aria-selected="false">Class</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#form-tabs-social" role="tab"
                                aria-selected="false">Book</button>
                        </li>
                    </ul>
                </div>

                <div class="tab-content">
                    <!-- Personal Info -->
                    <div class="tab-pane fade active show" id="form-tabs-personal" role="tabpanel">
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="row">
                                        <label class="col-sm-3 col-form-label text-sm-end" for="board-name">Board
                                            Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="board-name" name="board-name" class="form-control"
                                                placeholder="Enter Board Name" />
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="row justify-content-end">
                                        <div class="col-sm-9">
                                            <button type="button" onclick="addBoard()"
                                                class="btn btn-primary me-sm-3 me-1">Submit</button>
                                            <button type="reset" class="btn btn-label-secondary">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Account Details -->
                    <div class="tab-pane fade" id="form-tabs-account" role="tabpanel">
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="row">
                                        <label class="col-sm-3 col-form-label text-sm-end" for="class-name">Class
                                            Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="class-name" name="class-name" class="form-control"
                                                placeholder="Enter Class Name" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="row justify-content-end">
                                        <div class="col-sm-9">
                                            <button type="button" onclick="addClass()"
                                                class="btn btn-primary me-sm-3 me-1">Submit</button>
                                            <button type="reset" class="btn btn-label-secondary">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Social Links -->
                    <div class="tab-pane fade" id="form-tabs-social" role="tabpanel">
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="row">
                                        <label class="col-sm-3 col-form-label text-sm-end" for="book-name">Book
                                            Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="book-name" name="book-name" class="form-control"
                                                placeholder="Enter Book Name" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="row justify-content-end">
                                        <div class="col-sm-9">
                                            <button type="button" onclick="addBook()"
                                                class="btn btn-primary me-sm-3 me-1">Submit</button>
                                            <button type="reset" class="btn btn-label-secondary">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-5">

    <div class="row">
        <div class="col">
            <h6 class="mt-4">Listing</h6>
            <div class="card mb-3">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab"
                                data-bs-target="#form-tabs-board-listing" role="tab"
                                aria-selected="true">Boards</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link " data-bs-toggle="tab" data-bs-target="#form-tabs-class-listing"
                                role="tab" aria-selected="false">Classes</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#form-tabs-book-listing"
                                role="tab" aria-selected="false">Books</button>
                        </li>
                    </ul>
                </div>

                <div class="tab-content">
                    <!-- Personal Info -->
                    <div class="tab-pane fade active show" id="form-tabs-board-listing" role="tabpanel">
                        {{-- <div class="card"> --}}
                        <h5 class="card-header">Boards</h5>
                        <div class="card-datatable text-nowrap">
                            <table class="datatables-ajax table" id="boards-table">
                                <thead>
                                    <tr>
                                        <th>SR#</th>
                                        <th>Board</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        {{-- </div> --}}
                    </div>
                    <!-- Account Details -->
                    <div class="tab-pane fade" id="form-tabs-class-listing" role="tabpanel">
                        {{-- <div class="card"> --}}
                        <h5 class="card-header">Classes</h5>
                        <div class="card-datatable text-nowrap">
                            <table class="datatables-ajax table" id="classes-table">
                                <thead>
                                    <tr>
                                        <th>SR#</th>
                                        <th>Class</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        {{-- </div> --}}
                    </div>
                    <!-- Social Links -->
                    <div class="tab-pane fade" id="form-tabs-book-listing" role="tabpanel">
                        {{-- <div class="card"> --}}
                        <h5 class="card-header">Books</h5>
                        <div class="card-datatable text-nowrap">
                            <table class="datatables-ajax table" id="books-table">
                                <thead>
                                    <tr>
                                        <th>SR#</th>
                                        <th>Book</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="books-table-body">
                                </tbody>
                            </table>
                        </div>
                        {{-- </div> --}}
                    </div>

                    {{-- update model for board --}}
                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUpdateBoard"
                        aria-labelledby="offcanvasUpdateBoardLabel">
                        <div class="offcanvas-header">
                            <h5 id="offcanvasUpdateBoardLabel" class="offcanvas-title">Edit Board</h5>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body mx-0 flex-grow-0">
                            <form class="update-board pt-0" id="updateBoardForm">
                                <div class="mb-3">
                                    <label class="form-label" for="board-name">Board Name</label>
                                    <input type="text" class="form-control" required id="update-board-name"
                                        placeholder="FBISE" name="name" aria-label="BOARD" />
                                </div>
                                <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Update</button>
                                <button type="reset" class="btn btn-label-secondary"
                                    data-bs-dismiss="offcanvas">Cancel</button>
                            </form>
                        </div>
                    </div>

                    {{-- update model for book --}}
                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUpdateBook"
                        aria-labelledby="offcanvasUpdateBookLabel">
                        <div class="offcanvas-header">
                            <h5 id="offcanvasUpdateBookLabel" class="offcanvas-title">Edit Book</h5>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body mx-0 flex-grow-0">
                            <form class="update-class pt-0" id="addBookForm">
                                <div class="mb-3">
                                    <label class="form-label" for="class-name">Book Name</label>
                                    <input type="text" class="form-control" required id="update-book-name"
                                        placeholder="FBISE" name="name" aria-label="BOARD" />
                                </div>
                                <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Update</button>
                                <button type="reset" class="btn btn-label-secondary"
                                    data-bs-dismiss="offcanvas">Cancel</button>
                            </form>
                        </div>
                    </div>

                    {{-- update model for class --}}
                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUpdateClass"
                        aria-labelledby="offcanvasUpdateClassLabel">
                        <div class="offcanvas-header">
                            <h5 id="offcanvasUpdateClassLabel" class="offcanvas-title">Edit Class</h5>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body mx-0 flex-grow-0">
                            <form class="update-class pt-0" id="addClassForm">
                                <div class="mb-3">
                                    <label class="form-label" for="class-name">Class Name</label>
                                    <input type="text" class="form-control" required id="update-class-name"
                                        placeholder="FBISE" name="name" aria-label="class" />
                                </div>
                                <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Update</button>
                                <button type="reset" class="btn btn-label-secondary"
                                    data-bs-dismiss="offcanvas">Cancel</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Include the following script at the end of the view -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function editBook(id) {
        alert(id)
        var offcanvasElement = document.getElementById('offcanvasUpdateBook');
        var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
        offcanvas.show();
    }

    function editClass(id) {
        alert(id)
        var offcanvasElement = document.getElementById('offcanvasUpdateClass');
        var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
        offcanvas.show();
    }

    function editBoard(id) {
        alert(id)
        var offcanvasElement = document.getElementById('offcanvasUpdateBoard');
        var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
        offcanvas.show();
    }

    function addBoard() {
        const toastAnimationExample = document.querySelector('.toast-ex');
        var boardName = $('#board-name').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('board.store') }}',
            data: {
                _token: '{{ csrf_token() }}',
                name: boardName
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
                $('#board-name').val('');
                getData();
            },
            error: function(xhr) {

                var response = JSON.parse(xhr.responseText);
                var status = response.status;
                var message = response.message;

                console.log(status);
                console.log(message);

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

    function addBook() {
        const toastAnimationExample = document.querySelector('.toast-ex');
        var bookName = $('#book-name').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('book.store') }}',
            data: {
                _token: '{{ csrf_token() }}',
                name: bookName
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
                $('#book-name').val('');
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

    function addClass() {
        const toastAnimationExample = document.querySelector('.toast-ex');
        var className = $('#class-name').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('class.store') }}',
            data: {
                _token: '{{ csrf_token() }}',
                name: className
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
                $('#class-name').val('');
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

    function getData() {
        $.ajax({
            url: "{{ url('getBoardBookClass') }}",
            type: "GET",
            dataType: "json",
            success: function(response) {
                // Populate the Books table
                var booksTableBody = $("#books-table tbody");
                var dataTable = $('#books-table').DataTable();
                dataTable.destroy();
                $('#books-table').find('tbody').empty();
                $.each(response.Books, function(index, book) {
                    var row = "<tr>" +
                        "<td>" + (index + 1) + "</td>" +
                        "<td>" + book.name + "</td>" +
                        "<td>" +
                        "<button  onclick=\"editBook('" + book.id +
                        "')\" class=\"btn btn-sm btn-icon edit-record\"data-id='" + book.id +
                        "'><i class=\"ti ti-edit\"></i></button>" +
                        // "<button class=\"btn btn-sm btn-icon delete-record\" data-id='" + book.id +
                        // "'><i class=\"ti ti-trash\"></i></button>" +
                        "</td>" +
                        "</tr>";
                    booksTableBody.append(row);
                });

                // Populate the Boards table
                var boardsTableBody = $("#boards-table tbody");
                var dataTable = $('#boards-table').DataTable();
                dataTable.destroy();
                $('#boards-table').find('tbody').empty();
                $.each(response.Boards, function(index, board) {
                    var row = "<tr>" +
                        "<td>" + (index + 1) + "</td>" +
                        "<td>" + board.name + "</td>" +
                        "<td>" +
                        "<button  onclick=\"editBoard('" + board.id +
                        "')\" class=\"btn btn-sm btn-icon edit-record\"data-id='" + board.id +
                        "'><i class=\"ti ti-edit\"></i></button>" +
                        // "<button class=\"btn btn-sm btn-icon delete-record\" data-id='" + board.id +
                        // "'><i class=\"ti ti-trash\"></i></button>" +
                        "</td>" +
                        "</tr>";
                    boardsTableBody.append(row);
                });

                // Populate the Classes table
                var classesTableBody = $("#classes-table tbody");
                var dataTable = $('#classes-table').DataTable();
                dataTable.destroy();
                $('#classes-table').find(
                        'tbody')
                    .empty();
                $.each(response.Classes, function(index, classs) {
                    var row = "<tr>" +
                        "<td>" + (index + 1) + "</td>" +
                        "<td>" + classs.name + "</td>" +
                        "<td>" +
                        "<button  onclick=\"editClass('" + classs.id +
                        "')\" class=\"btn btn-sm btn-icon edit-record\"data-id='" + classs.id +
                        "'><i class=\"ti ti-edit\"></i></button>" +
                        // "<button class=\"btn btn-sm btn-icon delete-record\" data-id='" + classs
                        // .id + "'><i class=\"ti ti-trash\"></i></button>" +
                        "</td>" +
                        "</tr>";
                    classesTableBody.append(row);

                });
                var dt_ajax_table = $('.datatables-ajax');
                var dt_ajax = dt_ajax_table.dataTable({
                    processing: true,
                    // ajax: assetsPath + 'json/ajax.php',
                    dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>'
                });
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });

    }
    $(document).ready(function() {
        getData();
    });
    $(document).on('click', '.delete-record', function() {
        var user_id = $(this).data('id'),
            dtrModal = $('.dtr-bs-modal.show');

        // hide responsive modal in small screen
        if (dtrModal.length) {
            dtrModal.modal('hide');
        }

        // sweetalert for confirmation of delete
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
                // delete the data
                $.ajax({
                    type: 'DELETE',
                    url: "".concat(baseUrl, "user-list/").concat(user_id),
                    success: function success() {
                        dt_user.draw();
                    },
                    error: function error(_error) {
                        console.log(_error);
                    }
                });

                // success sweetalert
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: 'The user has been deleted!',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: 'Cancelled',
                    text: 'The User is not deleted!',
                    icon: 'error',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });
            }
        });
    });
</script>
