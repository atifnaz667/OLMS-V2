@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')
@section('title', 'Suggestions')
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
        Suggestion
    </h4>
    <!-- Sticky Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div
                    class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                    <h5 class="card-title mb-sm-0 me-2">Listing</h5>
                    <div class="action-btns">
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md">
                            <label class="form-label" for="board_id">User</label>
                            <input type="text" name="user" id="user" placeholder="Search By user" class="form-control">
                        </div>
                        <div class="col-md">
                            <label class="form-label" for="class_id">Date</label>
                            <input type="date" name="date" id="date" class="form-control">
                        </div>

                    </div>
                    <div
                        class="card-header sticky-element  d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                        <h5 class="card-title mb-sm-0 me-2"></h5>
                        <div class="action-btns">
                            <button type="button" onclick="fetchSuggestionRecords()" class="btn btn-primary"
                                id="filterButton">Filter</button>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Sr#</th>
                                <th>User</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Date</th>
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

@endsection

@section('page2-script')
    <script>
        var currentPage = 1;
        var lastPage = 1;
        var perPage = 10;
        const toastAnimationExample = document.querySelector('.toast-ex');



        function fetchSuggestionRecords(page = 1) {
            var user = $('#user').val();
            var date = $('#date').val();

            $.ajax({
                url: '{{ route('fetchSuggestionRecords') }}',
                method: 'GET',
                data: {
                    user: user,
                    date: date,
                    page: page,
                    perPage: perPage
                },
                success: function(response) {
                    var tableBody = $('.table tbody');
                    tableBody.empty();

                    if (response.status === 'success') {
                        var suggestions = response.data;
                        currentPage = response.current_page;
                        lastPage = response.last_page;

                        if (suggestions && suggestions.length > 0) {
                            $.each(suggestions, function(index, suggestion) {
                                var row = '<tr>' +
                                    '<td>' + (index + 1) + '</td>' +
                                    '<td>' + suggestion.user + '</td>' +
                                    '<td>' + suggestion.subject + '</td>' +
                                    '<td>' + suggestion.message + '</td>' +
                                    '<td>' + suggestion.date + '</td>' +
                                    "<td>" +
                                    "<a class=\"btn-icon delete-suggestion\" data-id='" +
                                    suggestion.id +
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

        $(document).on('click', '.delete-suggestion', function() {
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
                        type: 'get',
                        url: "{{ route('suggestion.destroy', '') }}" + "/" + user_id,
                        data: {
                            _token: _token,
                        },
                        success: function success(response) {
                            fetchSuggestionRecords(currentPage)
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

        $('#filterButton, #perPageSelect').on('click change', function(e) {
            e.preventDefault();
            currentPage = 1; // Reset to first page when filter is applied
            perPage = $('#perPageSelect').val();
            fetchSuggestionRecords();
        });

        // Handle pagination click event
        $(document).on('click', '.pagination-link', function(e) {
            e.preventDefault();
            var page = $(this).attr('data-page');
            if (page !== currentPage) {
                fetchSuggestionRecords(page);
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
        fetchSuggestionRecords();
    </script>
@endsection
