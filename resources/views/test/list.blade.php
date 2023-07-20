@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')
@section('title', 'Tests')
<style>
    .pagination-nav {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
        margin-right: 20px;
    }
</style>

@section('content')

    <h4 class="fw-bold py-1 ">
        <span class="text-muted fw-light">Home/</span>
        Digital task
    </h4>
    <!-- Sticky Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card" style="border-top: 3px solid #7367f0">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md">
                            <label class="form-label" for="board_id">Date From</label>
                            <input type="date" name="from" id="from" class="form-control">
                        </div>
                        <div class="col-md">
                            <label class="form-label" for="board_id">Date To</label>
                            <input type="date" name="to" id="to" class="form-control">
                        </div>
                        <div class="col-md">
                            <label class="form-label" for="class_id">Test Status</label>
                            <select id="status" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select Status</option>
                                <option value="Pending">Pending</option>
                                <option value="Attempted">Attempted</option>
                            </select>
                        </div>
                        <div class="col-md">
                            <label class="form-label" for="class_id">Test Type</label>
                            <select id="type" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select Test Type</option>
                                <option value="Self">Self</option>
                                <option value="Parent">Parent</option>
                                <option value="Monthly">Monthly</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-4">
                      <div class="col-12 d-flex justify-content-end">
                        <button type="button" onclick="fetchTestRecords()" class="btn btn-primary"
                            id="filterButton">Filter</button>
                      </div>
                    </div>
                </div>
                <hr>
                {{-- table  --}}
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Sr#</th>
                                @if (Auth::user()->role_id == 4)
                                  <th>Created By</th>
                                @else
                                  <th>Created For</th>
                                @endif
                                <th>Book</th>
                                <th>Status</th>
                                <th>Test Type</th>
                                <th>Total Marks</th>
                                <th>Obtainded Marks</th>
                                <th>Created At </th>
                                <th>Test Date </th>
                                <th>Attempted At </th>
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
        // var offcanvasElement = document.getElementById('offcanvasUpdateTest');
        // var offcanvas = new bootstrap.Offcanvas(offcanvasElement);

        function fetchTestRecords(page = 1) {
            var from = $('#from').val();
            var to = $('#to').val();
            var status = $('#status').val();
            var type = $('#type').val();

            $.ajax({
                url: '{{ route('fetchTestsRecords') }}',
                method: 'GET',
                data: {
                    from: from,
                    to: to,
                    status: status,
                    type: type,
                    page: page,
                    perPage: perPage
                },
                success: function(response) {
                    var tableBody = $('.table tbody');
                    tableBody.empty();
                    if (response.status === 'success') {
                        var tests = response.data;
                        currentPage = response.current_page;
                        lastPage = response.last_page;

                        if (tests && tests.length > 0) {

                            $.each(tests, function(index, test) {
                              let td = '';
                              if (test.status2 == 'Pending') {
                                td = '<td> '+ test.formStart +' @csrf '+ test.formEnd +'</td>';
                              }else{
                                td = '<td> <a href="result?test_id='+test.id+'" class="btn btn-sm btn-success " >  Result </a> </td>';
                              }
                                var row = '<tr>' +
                                    '<td>' + (index + 1) + '</td>' +
                                    '<td>' + test.user + '</td>' +
                                    '<td>' + test.book + '</td>' +
                                    '<td>' + test.status + '</td>' +
                                    '<td>' + test.test_type + '</td>' +
                                    '<td>' + test.total_marks + '</td>' +
                                    '<td>' + test.obtained_marks + '</td>' +
                                    '<td>' + test.created_at + '</td>' +
                                    '<td>' + test.test_date + '</td>' +
                                    '<td>' + test.attempted_at  + '</td>' +
                                    td+
                                    '</tr>';
                                tableBody.append(row);
                            });
                        }else{
                          var row = '<tr>' +
                            '<th colspan="8" class="text-center"><center>Record Not found</th>' +
                            '</tr>';
                        tableBody.append(row);
                        }
                    } else {
                      console.log('Record not found')
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
            fetchTestRecords();
        });

        // Handle pagination click event
        $(document).on('click', '.pagination-link', function(e) {
            e.preventDefault();
            var page = $(this).attr('data-page');
            if (page !== currentPage) {
                fetchTestRecords(page);
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
        fetchTestRecords();
    </script>
@endsection
