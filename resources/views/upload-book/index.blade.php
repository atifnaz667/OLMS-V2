@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')
@section('title', 'Upload Book Pdf')
@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Home/</span>
        Upload Book Pdf
    </h4>

    <form id="bookUploadForm" class="form-repeater">
        <div class="row">
            @csrf
            <div class="col-12">
                <div class="card">
                    <div
                        class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                        <h5 class="card-title mb-sm-0 me-2">Upload Book Pdf</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md">
                                <label class="form-label" for="board_id">Board</label>
                                <select id="board_id" name="board_id" class="select2 form-select" required
                                    data-allow-clear="true">
                                    <option value="">Select</option>
                                    @foreach ($boards as $board)
                                        <option value="{{ $board->id }}">{{ $board->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md">
                                <label class="form-label" for="class_id">Class</label>
                                <select id="class_id" name="class_id" class="select2 form-select" required
                                    data-allow-clear="true">
                                    <option value="">Select</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md">
                                <label class="form-label" for="book_id">Book</label>
                                <select id="book_id" name="book_id" class="select2 form-select" required
                                    data-allow-clear="true">
                                    <option value="">Select</option>
                                    @foreach ($books as $book)
                                        <option value="{{ $book->id }}">{{ $book->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md">
                                <label class="form-label" for="book_file">Upload Book Pdf</label>
                                <input type="file" class="form-control" id="book_file" name="book_file"
                                    placeholder="Full Name" />
                            </div>
                            <div class="col-md">
                                <label class="form-label" for="book_link">Upload Book Pdf from Link</label>
                                <input type="text" class="form-control" id="book_link" name="book_link"
                                    placeholder="Enter PDF link">
                            </div>
                            <div class="col-md">
                                <label class="form-label" for="bookIcon">Book Icon</label>
                                <input type="file" class="form-control" id="bookIcon" name="bookIcon" required>
                            </div>
                        </div>

                        <div
                            class="card-header sticky-element d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                            <h5 class="card-title mb-sm-0 me-2"></h5>
                            <div class="action-btns">
                                <button type="button" id="submitBookPdf" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
        <br>
    </form>

    <!-- /Form Repeater -->
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th>Sr#</th>
                    <th>Board</th>
                    <th>Book</th>
                    <th>Class</th>
                    <th>Icon</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">

            </tbody>
        </table>
    </div>
    <nav aria-label="Page navigation" class="pagination-nav">
        <ul class="pagination"></ul>
    </nav>

@endsection

@section('page2-script')
    <script>
        $(document).ready(function() {

            $('#submitBookPdf').click(function() {
                const toastAnimationExample = document.querySelector('.toast-ex');
                var form = $('#bookUploadForm');
                if (form[0].checkValidity() === false) {
                    form.addClass('was-validated');
                    return;
                }

                var formData = new FormData();
                formData.append('book_id', $('#book_id').val());
                formData.append('class_id', $('#class_id').val());
                formData.append('board_id', $('#board_id').val());
                formData.append('bookIcon', $('#bookIcon').val());
                if ($('#bookIcon')[0].files.length > 0) {
                    var file_data = $('#bookIcon').prop('files')[0];
                    formData.append('bookIcon', file_data);
                } else
                    formData.append('bookIcon', null);

                // Check if a file is provided
                if ($('#book_file')[0].files.length > 0) {
                    var file_data = $('#book_file').prop('files')[0];
                    formData.append('file', file_data);
                } else if ($('#book_link').val() !== '') {
                    var pdfLink = $('#book_link').val();
                    formData.append('pdf_link', pdfLink);
                } else {
                    $('.toast-ex .fw-semibold').text('error');
                    $('.toast-ex .toast-body').text('Please upload a file or provide a link.');
                    selectedType = "text-warning";
                    selectedAnimation = "animate__fade";
                    toastAnimationExample.classList.add(selectedAnimation);
                    toastAnimationExample.querySelector('.ti').classList.add(selectedType);
                    toastAnimation = new bootstrap.Toast(toastAnimationExample);
                    toastAnimation.show();
                    return;
                }


                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('book/upload') }}',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response);

                        $('#bookUploadForm [data-repeater-item]').remove();
                        var status = response.status;
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
            });


            function fetchUploadedBookPdfRecords() {
                var book_id = $('#book_id').val();
                var class_id = $('#class_id').val();
                var board_id = $('#board_id').val();
                var check = "ajax";
                $.ajax({
                    url: '{{ route('fetchUploadedBookPdfRecords') }}',
                    method: 'GET',
                    // data: {
                    //     book_id: book_id,
                    //     class_id: class_id,
                    //     board_id: board_id,
                    //     check: check,
                    //     page: page,
                    //     perPage: perPage
                    // },
                    success: function(response) {
                        // console.log(response);

                        var tableBody = $('.table tbody');
                        tableBody.empty();

                        if (response.status === 'success') {
                            var fetchRecords = response.data;
                            currentPage = response.current_page;
                            lastPage = response.last_page;
                            console.log(fetchRecords)

                            if (fetchRecords && fetchRecords.length > 0) {

                                $.each(fetchRecords, function(index, fetchRecord) {
                                    var pdfFilePath = baseUrl + 'files/booksPdf/' + fetchRecord
                                        .book_pdf;
                                    var iconHtml = (fetchRecord.book_icon != null) ? `<img src='files/booksIcon/${fetchRecord.book_icon}' alt='Book Icon' width='50' height='50'>` : '';
                                    var row = `<tr>
                                <td>${index + 1}</td>
                                <td>${fetchRecord.board}</td>
                                <td>${fetchRecord.book}</td>
                                <td>${fetchRecord.class}</td>
                                <td>${iconHtml}</td>
                                <td>
                                <a href="${pdfFilePath}" target="_blank"><i class="fas fa-download"></i> </a>
                                    <button class="btn btn-sm btn-icon delete-book-pdf" data-id="${fetchRecord.id}">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </td>
                            </tr>`;
                                    tableBody.append(row);
                                });
                            }
                        } else {
                            console.error(response.message);
                        }

                        // updatePaginationUI();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
            $(document).on('click', '.delete-book-pdf', function() {
                var _token = $('input[name="_token"]').val();
                var book_pdf_id = $(this).data('id'),
                    dtrModal = $('.dtr-bs-modal.show');
                if (dtrModal.length) {
                    dtrModal.modal('hide');
                }
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to delete this!",
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
                            url: "{{ route('bookPdf/destroy', '') }}" + "/" + book_pdf_id,
                            data: {
                                _token: _token,
                            },
                            success: function success(response) {
                                fetchUploadedBookPdfRecords();
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
                                    title: status.charAt(0).toUpperCase() +
                                        status.slice(1),
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



            fetchUploadedBookPdfRecords();
        });
    </script>
@endsection
