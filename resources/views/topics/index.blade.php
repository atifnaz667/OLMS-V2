@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')


@section('title', 'Chapters')


@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/jquery-sticky/jquery-sticky.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
@endsection

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
        Topics
    </h4>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div
                    class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                    <h5 class="card-title mb-sm-0 me-2">Listing</h5>
                    <div class="action-btns">
                        <button class="btn btn-primary">
                            Add Record
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md">
                            <label class="form-label" for="state">Board</label>
                            <select id="board_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                                @foreach ($boards as $board)
                                    <option value="{{ $board->id }}">{{ $board->name }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md">
                            <label class="form-label" for="state">Class</label>
                            <select id="class_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md">
                            <label class="form-label" for="state">Book</label>
                            <select id="book_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md">
                            <label class="form-label" for="state">Chapter</label>
                            <select id="chapter_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>

                    <div
                        class="card-header sticky-element  d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                        <h5 class="card-title mb-sm-0 me-2"></h5>
                        <div class="action-btns">
                            <button type="button" onclick="fetchTopicRecords()" class="btn btn-primary">Filter</button>
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
                                <th>Topic</th>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#board_id, #book_id, #class_id').change(function() {
            var boardId = $('#board_id').val();
            var bookId = $('#book_id').val();
            var classId = $('#class_id').val();

            $.ajax({
                url: '{{ route('chapterDropDown') }}',
                method: 'GET',
                data: {
                    board: $('#board_id').val(),
                    book: $('#book_id').val(),
                    class: $('#class_id').val()
                },
                success: function(response) {
                    var chapterSelect = $('#chapter_id');
                    chapterSelect.empty().append('<option value="">Select</option>');

                    if (response.status === 'success') {
                        var chapters = response.Chapters;
                        if (chapters && chapters.length > 0) {
                            $.each(chapters, function(index, chapter) {
                                chapterSelect.append('<option value="' + chapter
                                    .id + '">' + chapter.name + '</option>');
                            });
                        }
                    } else {
                        console.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });

        });
    });
</script>
<script>
    var currentPage = 1;
    var lastPage = 1;
    var perPage = 10;

    function fetchTopicRecords(page = 1) {
        var chapterId = $('#chapter_id').val();
        var check = "ajax";
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
                                '<td><a href="#" class="btn btn-primary">Edit</a></td>' +
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


    // Trigger fetchTopicRecords() on filter button click
    $('#filterButton, #perPageSelect').on('click change', function(e) {
        e.preventDefault();
        currentPage = 1; // Reset to first page when filter is applied
        perPage = $('#perPageSelect').val();
        fetchTopicRecords();
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

    // Initial fetch and pagination UI update
    // fetchTopicRecords();
</script>
