@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')
@section('title', 'Board Class Book')
@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
@endsection
@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <!-- Flat Picker -->
@endsection

@section('content')
    {{-- <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Home/</span>
        Books
    </h4> --}}
    @if (session('success'))
        <input type="hidden" class="hidden" value=" {{ session('success') }}">
    @endif
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

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="row">
                                    <label class="col-sm-3 col-form-label text-sm-end" for="board-name">Board
                                        Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" id="board-name" name="board-name" required
                                            class="form-control" placeholder="Enter Board Name" />
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

                    </div>
                    <!-- Account Details -->
                    <div class="tab-pane fade" id="form-tabs-account" role="tabpanel">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="row">
                                    <label class="col-sm-3 col-form-label text-sm-end" for="class-name">Class
                                        Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" id="class-name" name="class-name" required
                                            class="form-control" placeholder="Enter Class Name" />
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

                    </div>
                    <!-- Social Links -->
                    <div class="tab-pane fade" id="form-tabs-social" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="row">
                                    <label class="col-sm-3 col-form-label text-sm-end" for="book-name">Book Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" id="book-name" name="book-name" required class="form-control"
                                            placeholder="Enter Book Name" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <label class="col-sm-3 col-form-label text-sm-end" for="book-icon">Book Icon</label>
                                    <div class="col-sm-9">
                                        <input type="file" id="book-icon" name="book-icon" class="form-control"
                                            accept="image/*">
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
                                        <th>Icon</th>
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
                            <form class="update-board pt-0" id="addBoardForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label" for="board-name">Board Name</label>
                                    <input type="text" class="form-control" required id="update-board-name"
                                        placeholder="FBISE" name="update-board-name" aria-label="BOARD" />
                                    <input type="hidden" class="form-control" required id="boardId"
                                        placeholder="FBISE" name="boardId" aria-label="BOARD" />
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
                            {{-- <form class="update-book pt-0" id="updateBookForm"> --}}
                            <form action="{{ route('book.update', ['book' => 1]) }}" class="update-book pt-0"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label" for="class-name">Book Name</label>
                                    <input type="text" class="form-control" required id="updateBookName"
                                        placeholder="FBISE" name="updateBookName" aria-label="BOARD" />
                                    <input type="hidden" class="form-control" required id="bookId"
                                        placeholder="FBISE" name="bookId" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="updateBookIcon">Book Icon</label>
                                    <input type="file" id="updateBookIcon" name="updateBookIcon" class="form-control"
                                        accept="image/*">
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
                                        placeholder="FBISE" name="update-class-name" aria-label="class" />
                                    <input type="hidden" class="form-control" required id="classId"
                                        placeholder="FBISE" name="classId" />
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

@section('page2-script')
    <script>
        $(document).ready(function() {
            getData();
            if ($('.hidden').length > 0) {
                var successMessage = $('.hidden').val();
                var status = "success";
                $('.toast-ex .fw-semibold').text(status);
                $('.toast-ex .toast-body').text(successMessage);
                selectedType = "text-success";
                selectedAnimation = "animate__fade";
                toastAnimationExample.classList.add(selectedAnimation);
                toastAnimationExample.querySelector('.ti').classList.add(selectedType);
                toastAnimation = new bootstrap.Toast(toastAnimationExample);
                toastAnimation.show();
            }
        });
        const toastAnimationExample = document.querySelector('.toast-ex');
        var offcanvasElementbook = document.getElementById('offcanvasUpdateBook');
        var offcanvasbook = new bootstrap.Offcanvas(offcanvasElementbook);
        var offcanvasElementboard = document.getElementById('offcanvasUpdateBoard');
        var offcanvasboard = new bootstrap.Offcanvas(offcanvasElementboard);
        var offcanvasElementclass = document.getElementById('offcanvasUpdateClass');
        var offcanvasclass = new bootstrap.Offcanvas(offcanvasElementclass);

        function editBook(id) {
            $.ajax({
                url: "{{ route('book.show', '') }}" + "/" + id,
                type: 'GET',
                success: function(response) {
                    $('#updateBookName').val(response.Book.name);
                    $('#bookId').val(response.Book.id);
                    offcanvasbook.show();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    // Handle error if necessary
                }
            });

        }

        function editClass(id) {
            $.ajax({
                url: "{{ route('class.show', '') }}" + "/" + id,
                type: 'GET',
                success: function(response) {
                    $('#update-class-name').val(response.Class.name);
                    $('#classId').val(response.Class.id);
                    offcanvasclass.show();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    // Handle error if necessary
                }
            });
        }

        function editBoard(id) {
            $.ajax({
                url: "{{ route('board.show', '') }}" + "/" + id,
                type: 'GET',
                success: function(response) {
                    $('#update-board-name').val(response.Board.name);
                    $('#boardId').val(response.Board.id);
                    offcanvasboard.show();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    // Handle error if necessary
                }
            });
        }

        function updateBook(id) {
            var bookName = $('#updateBookName').val();
            var formData = new FormData();
            formData.append('name', bookName);
            formData.append('book-icon', $('#updateBookIcon')[0].files[0]);
            formData.append('bookId', id);
            formData.append('_token', '{{ csrf_token() }}');
            console.log(formData);
            $.ajax({
                url: "{{ route('book.update', '') }}" + "/" + id,
                type: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: formData,
                processData: false,
                contentType: false,
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
                    getData();
                    offcanvasbook.hide();

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

        function updateClass(id) {
            // Get the form data
            var _token = $('input[name="_token"]').val();
            var name = $('#update-class-name').val();
            var formData = {
                _token: _token,
                name: name
            };
            $.ajax({
                url: "{{ route('class.update', '') }}" + "/" + id,
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
                    getData();
                    offcanvasclass.hide();

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

        function updateBoard(id) {
            // Get the form data
            var _token = $('input[name="_token"]').val();
            var name = $('#update-board-name').val();
            var formData = {
                _token: _token,
                name: name
            };
            $.ajax({
                url: "{{ route('board.update', '') }}" + "/" + id,
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
                    getData();
                    offcanvasboard.hide();

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

        // Handle the form submission
        function UpdateClick() {
            var bookId = $('#bookId').val();
            updateBook(bookId);
        }
        $('#updateBookForm').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            var bookId = $('#bookId').val();
            updateBook(bookId);
        });
        $('#addClassForm').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            var classId = $('#classId').val();
            updateClass(classId);
        });
        $('#addBoardForm').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            var boardId = $('#boardId').val();
            updateBoard(boardId);
        });

        function addBook() {
            const toastAnimationExample = document.querySelector('.toast-ex');
            var bookName = $('#book-name').val();
            var formData = new FormData();
            formData.append('name', bookName);
            formData.append('book-icon', $('#book-icon')[0].files[0]);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                type: 'POST',
                url: '{{ route('book.store') }}',
                data: formData,
                processData: false,
                contentType: false,
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
                    $('#book-icon').val('');
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

        $(document).on('click', '.delete-class', function() {
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
                        url: "{{ route('class.destroy', '') }}" + "/" + user_id,
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

        $(document).on('click', '.delete-board', function() {
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
                        url: "{{ route('board.destroy', '') }}" + "/" + user_id,
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
                            "<td>" + (book.file != null ? "<img src='files/books/" + book.file +
                                "' alt='Book Icon' width='50' height='50'>" : "") + "</td>" +
                            "<td>" +
                            "<button onclick=\"editBook('" + book.id +
                            "')\" class=\"btn btn-sm btn-icon edit-record\" data-id='" + book.id +
                            "'><i class=\"ti ti-edit\"></i></button>" +
                            "<button class=\"btn btn-sm btn-icon delete-book\" data-id='" + book.id +
                            "'><i class=\"ti ti-trash\"></i></button>" +
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
                            "<button class=\"btn btn-sm btn-icon delete-board\" data-id='" + board.id +
                            "'><i class=\"ti ti-trash\"></i></button>" +
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
                            "<button class=\"btn btn-sm btn-icon delete-class\" data-id='" + classs
                            .id + "'><i class=\"ti ti-trash\"></i></button>" +
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
    </script>

@endsection
