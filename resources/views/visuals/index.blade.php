@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')
@section('title', 'Visuals')
@section('vendor-script')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
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
    @if (Session::has('status'))
      <input type="hidden" name="" id="tostStatus" value="{{ Session::get('status') }}">
      <input type="hidden" name="" id="tostMessage" value="{{ Session::get('message') }}">
      <input type="hidden" name="" id="tostType" value="{{ Session::get('status') == 'Success' ? 'text-success' : 'text-warning' }}">

      {{ Session::forget('status') }}
      {{ Session::forget('message') }}
    @endif
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Home/</span>
        Visuals
    </h4>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div
                    class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                    <h5 class="card-title mb-sm-0 me-2">Listing</h5>
                    <div class="action-btns">
                        <a href="{{ route('visual.create') }}" class="btn btn-primary">Add Record</a>

                    </div>
                </div>
                <div class="card-body">
                    <div class="row m-3">
                        <div class="col-md-3">
                            <label class="form-label" for="board_id">Board</label>
                            <select id="board_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                                @foreach ($boards as $board)
                                    <option value="{{ $board->id }}">{{ $board->name }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="class_id">Class</label>
                            <select id="class_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="book_id">Book</label>
                            <select id="book_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="chapter_id">Chapter</label>
                            <select id="chapter_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mx-3">
                        <div class="col-md-3">
                            <label class="form-label" for="topic_id">Topic</label>
                            <select id="topic_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="type">Type</label>
                            <select id="type" class="select2 form-select" data-allow-clear="true">
                                <option value="">All</option>
                                <option value="video">Videos</option>
                                <option value="image">Images</option>
                            </select>
                        </div>
                    </div>

                    <div class="card-header d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                        <h5 class="card-title mb-sm-0 me-2"></h5>
                        <div class="action-btns">
                            <button type="button" onclick="fetchVisualRecords()" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </div>

                <div class="row px-3 pb-3">
                  <div class="col-2">
                    <label class="form-label" for="state">Records per Page</label>
                      <select id="perPageSelect" class=" form-select" data-allow-clear="true">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                      </select>
                  </div>
                  <div class="col-10">
                    <label class="form-label" for="state">&nbsp;</label>

                    <input type="text" id="search-input" class="form-control" placeholder="Enter Title" >
                  </div>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Sr#</th>
                                <th>Type</th>
                                <th>Title</th>
                                <th>Topic</th>
                                <th>Visual</th>
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
    <div class="modal fade" id="largeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form class="update-class pt-0" action="{{ route('visual.update')}}" id="viewVisualForm" enctype="multipart/form-data" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel3">Visual</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="row mb-3">
                        <div class="col-6 col-sm-4">
                          <div class="form-group">
                            <label class="form-label" for="form-repeater-1-3">Visual Type</label>
                            <select id="visual_type" required name="visual_type" class="form-select" onchange="changeVisual(this.value)">
                                <option value="video">Video</option>
                                <option value="image">Image</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-6 col-sm-7">
                          <div class="form-group">
                            <label class="form-label" for="form-repeater-1-3">Visual</label>
                            <div id="visual-div">

                            </div>
                          </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col mb-3">
                                <input type="hidden" id="visualId" name="visualId" />
                                <label class="form-label" for="update-visual">Title</label>
                                <textarea required id='update-title'  name="title" rows="2" class="form-control" placeholder="Enter Title"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Save changes</button>
                    </div>
                </div>

        </div>
        </form>
    </div>
@endsection



@section('page2-script')
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
            $('#board_id, #book_id, #class_id, #chapter_id').change(function() {
                $.ajax({
                    url: '{{ route('topicDropDown') }}',
                    method: 'GET',
                    data: {

                        chapter: $('#chapter_id').val()
                    },
                    success: function(response) {
                        var topicSelect = $('#topic_id');
                        topicSelect.empty().append('<option value="">Select</option>');

                        if (response.status === 'success') {
                            var topics = response.Topics;
                            if (topics && topics.length > 0) {
                                $.each(topics, function(index, topic) {
                                    topicSelect.append('<option value="' + topic
                                        .id + '">' + topic.name + '</option>');
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
        var numbering = 1;
        const toastAnimationExample = document.querySelector('.toast-ex');

        function viewVisual(id) {
            $.ajax({
                url: "{{ route('visual.show', '') }}" + "/" + id,
                type: 'GET',
                success: function(response) {

                    $('#update-title').val(response.Visual.title);
                    $('#visualId').val(response.Visual.id);
                    let q_type = response.Visual.visual_type;
                    let visual_type = '<option value="'+response.Visual.visual_type+'">'+q_type.charAt(0).toUpperCase() + q_type.slice(1)+'</option>';
                    visual_type+='<option value="video">Video</option><option value="image">Image</option>';
                    $('#visual_type').html(visual_type);
                    let visual = '';
                    if (response.Visual.visual_type == 'video') {
                      visual = '<input type="text" name="path" id="path" class="form-control" placeholder="Enter youtube video url" value="'+ response.Visual.path+'">';
                    }else{
                      visual = '<input type="file" accept="image/*" name="path" id="path" class="form-control" >';
                    }
                    $('#visual-div').html(visual);
                    $('#largeModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    // Handle error if necessary
                }
            });
        }

        function fetchVisualRecords(page = 1) {
            var chapter_id = $('#chapter_id').val();
            var book_id = $('#book_id').val();
            var class_id = $('#class_id').val();
            var type = $('#type').val();
            var topicId = $('#topic_id').val();
            var board_id = $('#board_id').val();
            var searchQuery = $('#search-input').val();
            var check = "ajax";
            $.ajax({
                url: '{{ route('visual.index') }}',
                method: 'GET',
                data: {
                    chapter_id: chapter_id,
                    book_id: book_id,
                    class_id: class_id,
                    board_id: board_id,
                    topic_id: topicId,
                    type: type,
                    check: check,
                    page: page,
                    searchQuery: searchQuery,
                    perPage: perPage
                },
                success: function(response) {
                    var tableBody = $('.table tbody');
                    tableBody.empty();

                    if (response.status === 'success') {
                        var visuals = response.data;
                        currentPage = response.current_page;
                        lastPage = response.last_page;
                        numbering = (currentPage - 1) * response.per_page;
                        if (visuals && visuals.length > 0) {
                            $.each(visuals, function(index, visual) {
                                var row = '<tr>' +
                                    '<td>' + (++numbering) + '</td>' +
                                    '<td>' + visual.visual_type + '</td>' +
                                    '<td>' + visual.title + '</td>' +
                                    '<td>' + visual.topic + '</td>' +
                                    '<td>' + visual.path + '</td>' +
                                    "<td>" +
                                    "<a onclick=\"viewVisual('" + visual
                                    .id +
                                    "')\" class=\"btn-icon edit-record\"data-id='" + visual
                                    .id +
                                    "'><i class=\"ti ti-edit\"></i></a>" +
                                    "<a class=\"btn-icon delete-visual\" data-id='" +
                                    visual.id +
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

        $(document).on('click', '.delete-visual', function() {
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
                        url: "{{ route('visual.destroy', '') }}" + "/" + user_id,
                        data: {
                            _token: _token,
                        },
                        success: function success(response) {
                            fetchVisualRecords(currentPage)
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


        $('#filterButton, #perPageSelect').on('change', function(e) {
            e.preventDefault();
            currentPage = 1; // Reset to first page when filter is applied
            perPage = $('#perPageSelect').val();
            fetchVisualRecords();
        });

        // Handle pagination click event
        $(document).on('click', '.pagination-link', function(e) {
            e.preventDefault();
            var page = $(this).attr('data-page');
            if (page !== currentPage) {
                fetchVisualRecords(page);
            }
        });

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
        fetchVisualRecords();

        $("#search-input").keypress(function(e) {

          if(e.which == 13) {
            fetchVisualRecords();
          }
        });

        function changeVisual(visualType){
          let visual = '';
          if (visualType == 'video') {
            visual = '<input type="text" name="path" id="path" class="form-control" placeholder="Enter youtube video url">';
          }else{
            visual = '<input type="file" accept="image/*" name="path" id="path" class="form-control" >';
          }
          $("#visual-div").html(visual);
        }

        $(document).ready(function() {
          var status = $("#tostStatus").val();
          if (status) {
            var message = $("#tostMessage").val();
            showNotification(status,message);
          }
        });

        function showNotification(status,message){
          const toastAnimationExample = document.querySelector('.toast-ex');
          $('.toast-ex .fw-semibold').text(status);
          $('.toast-ex .toast-body').text(message);

          // Show the toast notification
          selectedType = $("#tostType").val();
          selectedAnimation = "animate__fade";
          toastAnimationExample.classList.add(selectedAnimation);
          toastAnimationExample.querySelector('.ti').classList.add(selectedType);
          toastAnimation = new bootstrap.Toast(toastAnimationExample);
          toastAnimation.show();
        }
    </script>
@endsection
