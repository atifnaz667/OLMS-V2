@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')
@section('title', 'Assigned Students')
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
        Assigned Students List
    </h4>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div
                    class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                    <h5 class="card-title mb-sm-0 me-2">Listing</h5>
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
                            <label class="form-label" for="book_id">Book</label>
                            <select id="book_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->name }}</option>
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
                    </div>

                    <div
                        class="card-header sticky-element  d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                        <h5 class="card-title mb-sm-0 me-2"></h5>
                        <div class="action-btns">
                            <button type="button" onclick="fetchAssignedStudentRecords()" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Sr#</th>
                                <th>Student Name</th>
                                <th>Board</th>
                                <th>Subject</th>
                                <th>Class</th>
                                <th>Actions</th>
                                <!-- <th>View Comment</th> -->
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                                       
                         </tbody>
                    </table>
                </div>
                <nav aria-label="Page navigation" class="pagination-nav">
                    <ul class="pagination"></ul>
                </nav>
                <!-- comment modal -->
                <div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document" style="max-width: 50%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="commentModalLabel">Comment</h5>
                                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button> -->
                            </div>
                            <div class="modal-body">
                            <input type="hidden" id="studentId" name="studentId" value="">
                            <div class="form-group">
                                <label for="commentTextarea">Add Comment:</label>
                                <textarea class="form-control" id="commentTextarea" name="commentTextarea" rows="4"></textarea>
                            </div>
                            </div>
                            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveComment()">Save</button>
            </div>
                        </div>
                    </div>
                </div>           
                
                 <!-- view comment modal -->
             <div class="modal fade" id="viewCommentModal" tabindex="-1" role="dialog"      aria-labelledby="viewCommentModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document" style="max-width: 50%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewCommentModalLabel">All Comments</h5>
                                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button> -->
                            </div>
                            <div class="modal-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sr#</th>
                                        <th>Date</th>
                                        <th>Comments</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="showData">
                                   
                                    <!-- Add more rows as needed -->
                                </tbody>
                            </table>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" onclick="closeModal()"  data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>    
                <!-- edit comment modal -->
                <div class="modal fade" id="editCommentModal" tabindex="-1" role="dialog" aria-labelledby="editCommentModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editCommentModalLabel">Edit Comment</h5>
                            </div>
                            <div class="modal-body">
                                <!-- Hidden input to store the comment ID -->
                                <input type="hidden" id="editCommentId" name="editCommentId">
                                <!-- <input type="text" id="editCommentValue" name="editCommentValue"> -->
                                <label for="commentTextarea">Comment</label>
                                <textarea class="form-control" id="editCommentValue" name="editCommentValue" rows="4"></textarea>
                                <!-- Other form fields for editing the comment -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" onclick="closeEditModal()" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="saveEditedComment()">Save</button>
                            </div>
                        </div>
                    </div>
                </div>

                <nav aria-label="Page navigation" class="pagination-nav">
                    <ul class="pagination"></ul>
                </nav>
            </div>
        </div>
    </div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUpdateChapter"
        aria-labelledby="offcanvasUpdateChapterLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasUpdateChapterLabel" class="offcanvas-title">Edit Topic</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0">
            <form class="update-class pt-0" id="addBookForm">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="update-topic-name">Topic Name</label>
                    <input type="text" class="form-control" required id="update-topic-name" name="update-topic-name"
                        aria-label="Chapter" />
                    <input type="hidden" class="form-control" required id="topicID" name="topicID" />
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
        function closeModal() {
                $('#commentModal').modal('hide');
                $('#viewCommentModal').modal('hide');
            }
        function closeEditModal() {
                $('#editCommentModal').modal('hide');
            }
            
        function viewCommentModal(studentId) {
            $.ajax({
            url: '{{ route('showComments') }}',
            method: 'Get',
            data: {
                _token: '{{ csrf_token() }}',
                student_id: studentId,
            },
            success: function(response) {
                
                
                      $('#showData').html(response.data); 
                       $('#viewCommentModal').modal('show'); 

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
    
        function openCommentModal(studentId) {
            $('#studentId').val(studentId); 
        $('#commentModal').modal('show'); // Show the modal
        }

      function saveComment() {
        var studentId = $('#studentId').val();
        var comment = $('#commentTextarea').val();

        $.ajax({
            url: '{{ route('saveComment') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                student_id: studentId,
                comment: comment
            },
            success: function(response) {
                        $('#commentModal').modal('hide');
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
       function openEditCommentModal(commentId) {
            $('#editCommentId').val(commentId);
       
            $.ajax({
            url: '{{ route('editComment') }}',
            method: 'Get',
            data: {
                _token: '{{ csrf_token() }}',
                comment_id: commentId,
            },
            success: function(response) {
                  console.log(response.commentText)
                  $('#editCommentValue').val(response.commentText);
                      $('#editCommentModal').modal('show');

                    },
            });
        }

        function saveEditedComment() {
        var editCommentId = $('#editCommentId').val();
        var editCommentValue = $('#editCommentValue').val();

        $.ajax({
            url: '{{ route('updateComment') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                editCommentId: editCommentId,
                editCommentValue: editCommentValue
            },
            success: function(response) {
                        $('#editCommentModal').modal('hide');
                        location.reload();
                        $('#viewCommentModal').modal('show');
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


        function fetchAssignedStudentRecords(page = 1) {
            var book_id = $('#book_id').val();
            var class_id = $('#class_id').val();
            var board_id = $('#board_id').val();
            var check = "ajax";
            $.ajax({
                url: '{{ route('fetchAssignedStudentRecords') }}',
                method: 'GET',
                data: {
                    book_id: book_id,
                    class_id: class_id,
                    board_id: board_id,
                    check: check,
                    page: page,
                    perPage: perPage
                },
                success: function(response) {
                    // console.log(response);

                    var tableBody = $('.table tbody');
                    tableBody.empty();

                    if (response.status === 'success') {
                        var students = response.data;
                        currentPage = response.current_page;
                        lastPage = response.last_page;
                        console.log(students)

                        if (students && students.length > 0) {
                            $.each(students, function(index, student) {
                                var row = '<tr>' +
                                    '<td>' + (index + 1) + '</td>' +
                                    '<td>' + student.student_name + '</td>' +
                                    '<td>' + student.board + '</td>' +
                                    '<td>' + student.book + '</td>' +
                                    '<td>' + student.class + '</td>' +
                                    "<td>" +
                                    "<a onclick=\"openCommentModal('" + student
                                    .student_id +
                                    "')\" class=\"btn-icon \"><i class=\"fas fa-comment ti-sm me-2\"></i></a>" +
                                    "<a onclick=\"viewCommentModal('" + student
                                    .student_id +
                                    "')\" class=\"btn-icon \"><i class=\"fas fa-eye ti-sm me-2\"></i></a>" +
                                    "</td>" +

                                    // "<td>" +
                                    // "<a onclick=\"viewCommentModal('" + student
                                    // .student_id +
                                    // "')\"><i class=\"fas fa-eye ti-sm me-2\"></i></a>"
                                    // "</td>" +

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
        $('#filterButton, #perPageSelect').on('click change', function(e) {
            e.preventDefault();
            currentPage = 1; // Reset to first page when filter is applied
            perPage = $('#perPageSelect').val();
            fetchAssignedStudentRecords();
        });

        // Handle pagination click event
        $(document).on('click', '.pagination-link', function(e) {
            e.preventDefault();
            var page = $(this).attr('data-page');
            if (page !== currentPage) {
                fetchAssignedStudentRecords(page);
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
        fetchAssignedStudentRecords();

    </script>
@endsection
