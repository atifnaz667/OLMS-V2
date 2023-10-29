@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')


@section('title', 'Boards')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('js/laravel-user-management.js') }}"></script>
    <script src="{{ asset('assets/js/ui-toasts.js') }}"></script>
@endsection



@section('content')
    <div class="bs-toast toast toast-ex animate__animated my-2" role="alert" aria-live="assertive" aria-atomic="true"
        data-bs-delay="4000">
        <div class="toast-header">
            <i class="ti ti-bell ti-xs me-2"></i>
            <div class="me-auto fw-semibold">Bootstrap</div>
            {{-- <small class="text-muted">11 mins ago</small> --}}
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Hello, world! This is a toast messagesss.
        </div>
    </div>
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Home/</span>
        Boards
    </h4>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-2">
                    <h5 class="card-title mb-0">Board Listing</h5>
                </div>
                <div class="col-md-8 sn-10"></div>
                <div class="col-md-2 sm-12">
                    <button class="btn btn-secondary add-new btn-primary" tabindex="0" aria-controls="DataTables_Table_0"
                        type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddBoard"><span><i
                                class="ti ti-plus me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Add New
                                User</span></span></button>
                </div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables table">
                <thead class="border-top">
                    <tr>
                        <th></th>
                        <th>SR#</th>
                        <th>Board</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($boards as $index => $board)
                        <tr>
                            <td></td>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $board->name }}</td>
                            <td>
                                <!-- Add action buttons here -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Offcanvas to add new board -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddBoard" aria-labelledby="offcanvasAddBoardLabel">
            <div class="offcanvas-header">
                <h5 id="offcanvasAddBoardLabel" class="offcanvas-title">Add Board</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body mx-0 flex-grow-0">
                <form class="add-new-board pt-0" id="addBoardForm">
                    <div class="mb-3">
                        <label class="form-label" for="board-name">Board Name</label>
                        <input type="text" class="form-control" required id="board-name" placeholder="FBISE"
                            name="name" aria-label="BOARD" />
                    </div>
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </form>
            </div>
        </div>
    </div>
@endsection

<!-- Include the following script at the end of the view -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // AJAX request to add a new board
    $(document).ready(function() {
        const toastAnimationExample = document.querySelector('.toast-ex');
        selectedType = "text-secondary";
        selectedAnimation = "animate__fade";
        toastAnimationExample.classList.add(selectedAnimation);
        toastAnimationExample.querySelector('.ti').classList.add(selectedType);

        $('#addBoardForm').on('submit', function(e) {
            e.preventDefault();

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
                    toastAnimation = new bootstrap.Toast(toastAnimationExample);
                    toastAnimation.show();
                },
                error: function(xhr) {
                    // Handle the error response
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
