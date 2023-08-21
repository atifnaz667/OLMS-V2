@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')
@section('title', 'Chapters')
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
        Unit
    </h4>
    <!-- Sticky Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div
                    class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                    <h5 class="card-title mb-sm-0 me-2">Listing</h5>
                    <div class="action-btns">
                        <a href="{{ route('add-chapter') }}" class="btn btn-primary">Add Record</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md">
                            <label class="form-label" for="board_id">Board</label>
                            <select id="board_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                                @foreach ($boards as $board)
                                    <option value="{{ $board->id }}">{{ $board->name }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md">
                            <label class="form-label" for="class_id">Class</label>
                            <select id="class_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md">
                            <label class="form-label" for="book_id">Book</label>
                            <select id="book_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div
                        class="card-header sticky-element  d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                        <h5 class="card-title mb-sm-0 me-2"></h5>
                        <div class="action-btns">
                            <button type="button" onclick="fetchChapterRecords()" class="btn btn-primary"
                                id="filterButton">Filter</button>
                        </div>
                    </div>
                </div>
                <hr>
                {{-- <div class="col-md-1">
                  <label class="form-label" for="state">Records per Page</label>
                    <select id="perPageSelect" class="select2 form-select" data-allow-clear="true">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                      </select>
                    </div> --}}
                {{-- table  --}}
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Sr#</th>
                                {{-- <th>Board</th>
                                <th>Book</th>
                                <th>Class</th> --}}
                                <th>Unit No.</th>
                                <th>Unit Name</th>
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
    {{-- update model for Chapter --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUpdateChapter"
        aria-labelledby="offcanvasUpdateChapterLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasUpdateChapterLabel" class="offcanvas-title">Edit Chapter</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0">
            <form class="update-class pt-0" id="addBookForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="update-chapter-number">Chapter Number</label>
                    <input type="text" class="form-control" required id="update-chapter-number"
                        name="update-chapter-number" aria-label="Chapter" />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="update-chapter-name">Chapter Name</label>
                    <input type="text" class="form-control" required id="update-chapter-name" name="update-chapter-name"
                        aria-label="Chapter" />
                    <input type="hidden" class="form-control" required id="chapterId" name="chapterId" />
                </div>
                <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Update</button>
                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
            </form>
        </div>
    </div>

@endsection

@section('page2-script')
    <script>
        var currentPage = 1;
        var lastPage = 1;
        var perPage = 10;
        const toastAnimationExample = document.querySelector('.toast-ex');
        var offcanvasElement = document.getElementById('offcanvasUpdateChapter');
        var offcanvas = new bootstrap.Offcanvas(offcanvasElement);

        function editChapter(id) {
            $.ajax({
                url: "{{ route('chapter.show', '') }}" + "/" + id,
                type: 'GET',
                success: function(response) {
                    // Update the form fields with the fetched data
                    $('#update-chapter-number').val(response.chapter.chapter_no);
                    $('#update-chapter-name').val(response.chapter.name);
                    $('#chapterId').val(response.chapter.id);

                    // Show the offcanvas
                    offcanvas.show();


                },
                error: function(xhr, status, error) {
                    console.error(error);
                    // Handle error if necessary
                }
            });
        }

        function updateChapter(id) {
            // Get the form data
            var _token = $('input[name="_token"]').val();
            var chapterNumber = $('#update-chapter-number').val();
            var chapterName = $('#update-chapter-name').val();
            var formData = {
                chapter_no: chapterNumber,
                _token: _token,
                name: chapterName
            };
            $.ajax({
                url: "{{ route('chapter.update', '') }}" + "/" + id,
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
                    fetchChapterRecords(currentPage)
                    offcanvas.hide();

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

        $('#addBookForm').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            var chapterId = $('#chapterId').val();
            updateChapter(chapterId);
        });

        function fetchChapterRecords(page = 1) {
            var boardId = $('#board_id').val();
            var bookId = $('#book_id').val();
            var classId = $('#class_id').val();
            var chapterId = $('#chapter_id').val();

            $.ajax({
                url: '{{ route('fetchChapterRecords') }}',
                method: 'GET',
                data: {
                    board_id: boardId,
                    book_id: bookId,
                    class_id: classId,
                    chapter_id: chapterId,
                    page: page,
                    perPage: perPage
                },
                success: function(response) {
                    var tableBody = $('.table tbody');
                    tableBody.empty();

                    if (response.status === 'success') {
                        var chapters = response.data;
                        currentPage = response.current_page;
                        lastPage = response.last_page;

                        if (chapters && chapters.length > 0) {
                            $.each(chapters, function(index, chapter) {
                                var row = '<tr>' +
                                    '<td>' + (index + 1) + '</td>' +
                                    // '<td>' + chapter.board + '</td>' +
                                    // '<td>' + chapter.book + '</td>' +
                                    // '<td>' + chapter.class + '</td>' +
                                    '<td>' + chapter.chapter_no + '</td>' +
                                    '<td>' + chapter.name + '</td>' +
                                    "<td>" +
                                    "<a onclick=\"editChapter('" + chapter
                                    .id +
                                    "')\" class=\"btn-icon edit-record\"data-id='" + chapter
                                    .id +
                                    "'><i class=\"ti ti-edit\"></i></a>" +
                                    "<a class=\"btn-icon delete-chapter\" data-id='" +
                                    chapter.id +
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

        $(document).on('click', '.delete-chapter', function() {
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
                        url: "{{ route('chapter.destroy', '') }}" + "/" + user_id,
                        data: {
                            _token: _token,
                        },
                        success: function success(response) {
                            fetchChapterRecords(currentPage)
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

        $('#filterButton, #perPageSelect').on('click change', function(e) {
            e.preventDefault();
            currentPage = 1; // Reset to first page when filter is applied
            perPage = $('#perPageSelect').val();
            fetchChapterRecords();
        });

        // Handle pagination click event
        $(document).on('click', '.pagination-link', function(e) {
            e.preventDefault();
            var page = $(this).attr('data-page');
            if (page !== currentPage) {
                fetchChapterRecords(page);
            }
        });

        // Update pagination UI
        function updatePaginationUI() {
            var paginationContainer = $('.pagination');
            paginationContainer.empty();

            if (lastPage > 1) {
                var paginationLinks = '';
                var maxVisiblePages = 5; // Set the maximum number of visible page links

                if (currentPage > 1) {
                    paginationLinks +=
                        '<li class="page-item first"><a class="page-link pagination-link" href="#" data-page="1"><i class="ti ti-chevrons-left ti-xs"></i></a></li>';
                    paginationLinks +=
                        '<li class="page-item prev"><a class="page-link pagination-link" href="#" data-page="' + (
                            currentPage - 1) + '"><i class="ti ti-chevron-left ti-xs"></i></a></li>';
                }

                var startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
                var endPage = Math.min(lastPage, startPage + maxVisiblePages - 1);

                for (var i = startPage; i <= endPage; i++) {
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

        // Initial fetch and pagination UI update
        fetchChapterRecords();
    </script>
@endsection
